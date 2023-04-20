<?php

namespace Tests\Feature\Jetstream\Fortify;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features as FortifyFeatures;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skip this test if email verification is not enabled within Fortify config
     */
    private function skip_test_if_fortify_email_verification_not_enabled(): void
    {
        if (!FortifyFeatures::enabled(FortifyFeatures::emailVerification())) {
            $this->markTestSkipped('Email verification not enabled.');
        }
    }

    /**
     * Test that the email verification screen can be rendered. When a user is created, they are not verified.
     *
     * @return void
     */
    public function test_email_verification_screen_can_be_rendered(): void
    {
        $this->skip_test_if_fortify_email_verification_not_enabled();

        $user = $this->create_unverified_user_with_personal_team();

        // acting as the user attempt to access the email verification screen
        $response = $this->actingAs($user)->get('/email/verify');

        // assert that the user sees the email verification screen confirmed by the status code 200
        $response->assertStatus(200);
    }

    /**
     * Test that a user can verify their email address.
     *
     * @return void
     */
    public function test_email_can_be_verified(): void
    {
        $this->skip_test_if_fortify_email_verification_not_enabled();

        // fake the event that is dispatched when a user is verified, do not execute any listeners
        Event::fake();

        $user = $this->create_unverified_user();

        // generate a temporary verification URL for the user
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // acting as the user, attempt to access the verification URL
        $response = $this->actingAs($user)->get($verificationUrl);

        // assert that the verification event was dispatched
        Event::assertDispatched(Verified::class);

        // assert that the user is verified
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // assert that the user is redirected to the home route with the query parameter ?verified=1
        $response->assertRedirect(RouteServiceProvider::HOME . '?verified=1');
    }

    /**
     * Test that a user can not verify their email address with an invalid hash.
     *
     * @return void
     */
    public function test_email_can_not_verified_with_invalid_hash(): void
    {
        // require laravel exception handling for this test -- otherwise the test will fail with the following error:
        // Illuminate\Auth\Access\AuthorizationException: This action is unauthorized.
        $this->withExceptionHandling();

        $this->skip_test_if_fortify_email_verification_not_enabled();

        $user = $this->create_unverified_user();

        // generate a temporary verification URL for the user with an invalid hash
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        // acting as the user, attempt to access the verification URL
        $this->actingAs($user)->get($verificationUrl);

        // assert that the user is not verified
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
