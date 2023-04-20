<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Jetstream\Features as JetstreamFeatures;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set up the test environment for the applications tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Always include the exception handling, so we can see the error
        $this->withoutExceptionHandling();
    }

    /**
     * Skip test if API support is not enabled within Jetstream config
     *
     * @return void
     */
    public function skip_test_if_jetstream_api_support_is_not_enabled(): void
    {
        if (!JetstreamFeatures::hasApiFeatures()) {
            $this->markTestSkipped('API support is not enabled.');
        }
    }

    /**
     * Create a new user and set the user as the currently logged-in user for the application.
     *
     * @return User
     */
    public function create_and_act_as_user(): User
    {
        $this->actingAs($user = User::factory()->create());

        return $user;
    }

    /**
     * Create a new user with a Jetstream personal team,
     * set the user as the currently logged-in user for the application.
     *
     * @return User
     */
    public function create_and_act_as_user_with_personal_team(): User
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        return $user;
    }

    /**
     * Return a created user that has not been verified.
     *
     * @return User
     */
    public function create_unverified_user(): User
    {
        return User::factory()->unverified()->create();
    }

    /**
     * Return a created user with a personal team that has not been verified.
     *
     * @return User
     */
    public function create_unverified_user_with_personal_team(): User
    {
        return User::factory()->withPersonalTeam()->unverified()->create();
    }


}
