<?php

namespace Tests\Feature\Jetstream\ApiFeature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that API tokens can be created.
     * Skip this test if API support is not enabled within Jetstream config
     *
     * @return void
     */
    public function test_api_tokens_can_be_created(): void
    {
        $this->skip_test_if_jetstream_api_support_is_not_enabled();

        $user = $this->create_and_act_as_user_with_personal_team();

        // create a new API token for the user within a livewire component
        Livewire::test(ApiTokenManager::class)
                ->set(['createApiTokenForm' => [
                    'name'        => 'Test Token',
                    'permissions' => [
                        'read',
                        'update',
                    ],
                ]])
                ->call('createApiToken');

        // assert that the API token was created
        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertEquals('Test Token', $user->fresh()->tokens->first()->name);
        // assert that the API token has the correct permissions
        $this->assertTrue($user->fresh()->tokens->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->first()->can('delete'));
    }
}
