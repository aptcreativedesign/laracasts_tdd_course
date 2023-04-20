<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JsonException;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * As a logged-in user, test that the confirm password screen can be rendered.
     *
     * @return void
     */
    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        // acting as a user attempt to access the confirm password screen
        $response = $this->actingAs($user)->get('/user/confirm-password');

        // assert that the user can access the confirm password screen
        $response->assertStatus(200);
    }

    /**
     * Test that a user can confirm their password.
     *
     * @return void
     * @throws JsonException
     */
    public function test_password_can_be_confirmed(): void
    {
        $user = User::factory()->create();

        // acting as a user attempt to confirm the password
        $response = $this->actingAs($user)->post('/user/confirm-password', [
            'password' => 'password',
        ]);

        // assert that the user is redirected to the intended route after confirming the password
        $response->assertRedirect();
        // assert that the session has no errors
        $response->assertSessionHasNoErrors();
    }

    /**
     * Test that a user can not confirm their password with an invalid password.
     *
     * @return void
     */
    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = User::factory()->create();

        // acting as a user attempt to confirm the password with an invalid password
        $response = $this->actingAs($user)->post('/user/confirm-password', [
            'password' => 'wrong-password',
        ]);

        // assert that the session has errors
        $response->assertSessionHasErrors();
    }
}
