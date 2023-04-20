<?php

namespace Tests\Feature\Jetstream\Fortify;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features as FortifyFeatures;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skip the test if Fortify registration feature is not enabled.
     *
     * @return void
     */
    private function skip_test_if_fortify_registration_not_enabled(): void
    {
        if (!FortifyFeatures::enabled(FortifyFeatures::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }
    }

    /**
     * Test the registration screen can be rendered if support is enabled.
     *
     * @return void
     */
    public function test_registration_screen_can_be_rendered(): void
    {
        $this->skip_test_if_fortify_registration_not_enabled();

        // attempt to access the registration screen
        $response = $this->get('/register');

        // assert that the user sees the registration screen confirmed by the status code 200
        $response->assertStatus(200);
    }

    /**
     * Test the registration screen cannot be rendered if support is disabled.
     *
     * @return void
     */
    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled(): void
    {
        // require laravel exception handling for this test -- otherwise the test will fail with the following error:
        // Symfony\Component\HttpKernel\Exception\NotFoundHttpException: GET
        $this->withExceptionHandling();

        // Skip the test if Fortify registration feature is enabled.
        if (FortifyFeatures::enabled(FortifyFeatures::registration())) {
            $this->markTestSkipped('Registration support is enabled.');
        }

        // attempt to access the registration screen
        $response = $this->get('/register');

        // assert that the user cannot see the registration screen confirmed by the status code 404
        $response->assertStatus(404);
    }

    /**
     * Test new users can register.
     *
     * @return void
     */
    public function test_new_users_can_register(): void
    {
        $this->skip_test_if_fortify_registration_not_enabled();

        // attempt to register a new user
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        // assert that the user is authenticated
        $this->assertAuthenticated();
        // assert that the user is redirected to the defined route
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
