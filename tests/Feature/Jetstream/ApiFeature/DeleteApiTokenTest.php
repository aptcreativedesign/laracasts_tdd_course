<?php

namespace Tests\Feature\Jetstream\ApiFeature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteApiTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a users API token can be deleted.
     *
     * @return void
     */
    public function test_api_tokens_can_be_deleted(): void
    {
        $this->skip_test_if_jetstream_api_support_is_not_enabled();

        $user = $this->create_and_act_as_user_with_personal_team();

        // create a new API token for the user
        $token = $user->tokens()->create([
            'name'      => 'Test Token',
            'token'     => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        // delete a users API token within a livewire component
        Livewire::test(ApiTokenManager::class)
                ->set(['apiTokenIdBeingDeleted' => $token->id])
                ->call('deleteApiToken');

        // assert that the API token was deleted
        $this->assertCount(0, $user->fresh()->tokens);
    }
}
