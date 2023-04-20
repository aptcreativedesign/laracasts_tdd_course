<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the login screen can be rendered.
     *
     * @return void
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test that a user can log in and authenticate using the login screen.
     *
     * @return void
     */
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        // create a user
        $user = User::factory()->create();

        // attempt to log in with email and the correct password
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        // assert that the user is authenticated
        $this->assertAuthenticated();
        // redirect to the route specified in RouteServiceProvider::HOME
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * Test that users can not authenticate with an invalid password.
     *
     * @return void
     */
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        // require laravel exception handling for this test -- otherwise the test will fail with the following error:
        // Illuminate\Validation\ValidationException: These credentials do not match our records
        $this->withExceptionHandling();

        // create a user
        $user = User::factory()->create();

        // attempt to log in with the wrong password
        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        // assert that the user is not authenticated
        $this->assertGuest();
    }
}
