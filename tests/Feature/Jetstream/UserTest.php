<?php

namespace Tests\Feature\Jetstream;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class UserTest extends TestCase
{
    use withFaker, RefreshDatabase;

    /**
     * Test that a user can register.
     *
     * @return void
     */
    public function test_a_user_can_register()
    {
        // test that a user can register
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        // assert that the user was registered
        $this->assertAuthenticated();
        // redirect to specified route
        $response->assertRedirect(RouteServiceProvider::HOME);

        // The following is the non-Jetstream test for registering a user
        //        $attributes = [
        //            'name' => 'Test User',
        //            'email' => 'test@example.com',
        //            'password' => 'password',
        //            'password_confirmation' => 'password',
        //            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        //        ];
        ////        $attributes["password_confirmation"] = $attributes["password"];
        //
        //        $this->post('/register', $attributes);

        //        $this->assertDatabaseHas('users', $attributes);
    }
}
