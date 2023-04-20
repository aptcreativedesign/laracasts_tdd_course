<?php

namespace Tests\Feature\Jetstream\Fortify;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features as FortifyFeatures;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skip the test if the Fortify password reset feature is not enabled.
     *
     * @return void
     */
    private function skip_test_if_fortify_password_reset_is_not_enabled(): void
    {
        if (!FortifyFeatures::enabled(FortifyFeatures::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');
        }
    }

    /**
     * Test the reset password link screen can be rendered.
     *
     * @return void
     */
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $this->skip_test_if_fortify_password_reset_is_not_enabled();

        // attempt to access the reset password link screen
        $response = $this->get('/forgot-password');

        // assert that the user sees the reset password link screen confirmed by the status code 200
        $response->assertStatus(200);
    }

    /**
     * Test that a reset password link can be requested.
     *
     * @return void
     */
    public function test_reset_password_link_can_be_requested(): void
    {
        $this->skip_test_if_fortify_password_reset_is_not_enabled();

        // Fake Notifications
        Notification::fake();

        $user = User::factory()->create();

        // enter the email address of the user and submit the form
        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        // assert that the user receives a password reset notification
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * Test that the reset password screen can be rendered after clicking the reset password link.
     *
     * @return void
     */
    public function test_reset_password_screen_can_be_rendered(): void
    {
        $this->skip_test_if_fortify_password_reset_is_not_enabled();

        // Fake Notifications
        Notification::fake();

        $user = User::factory()->create();

        // enter the email address of the user and submit the form
        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        // assert that the user receives a password reset notification
        Notification::assertSentTo($user, ResetPassword::class, function (object $notification) {
            // attempt to access the reset password screen
            $response = $this->get('/reset-password/' . $notification->token);

            // assert that the user sees the reset password screen confirmed by the status code 200
            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $this->skip_test_if_fortify_password_reset_is_not_enabled();

        // Fake Notifications
        Notification::fake();

        $user = User::factory()->create();

        // enter the email address of the user and submit the form
        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        // assert that the user receives a password reset notification
        Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
            // attempt to update the password from the reset password screen
            $response = $this->post('/reset-password', [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]);

            // assert that there are no errors in the session after updating the password
            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
