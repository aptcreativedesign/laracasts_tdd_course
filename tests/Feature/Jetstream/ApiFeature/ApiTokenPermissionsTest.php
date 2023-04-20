<?php

namespace Tests\Feature\Jetstream\ApiFeature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that API token permissions can be updated.
     * Skip this test if API support is not enabled within Jetstream config
     *
     * @return void
     */
    public function test_api_token_permissions_can_be_updated(): void
    {
        $this->skip_test_if_jetstream_api_support_is_not_enabled();

        $user = $this->create_and_act_as_user_with_personal_team();

        // create a new API token for the user
        $token = $user->tokens()->create([
            'name'      => 'Test Token',
            'token'     => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        // update a users a API token permissions within a livewire component
        Livewire::test(ApiTokenManager::class)
                ->set(['managingPermissionsFor' => $token])
                ->set(['updateApiTokenForm' => [
                    'permissions' => [
                        'delete',
                        'missing-permission',
                    ],
                ]])
                ->call('updateApiToken');

        // assert that the API token has the correct permissions
        $this->assertTrue($user->fresh()->tokens->first()->can('delete'));
        $this->assertFalse($user->fresh()->tokens->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->first()->can('missing-permission'));
    }
}
