<?php

namespace Tests\Feature\Jetstream\Teams;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTeamMemberRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that team member roles can be updated from a livewire component by the team owner.
     *
     * @return void
     */
    public function test_team_member_roles_can_be_updated(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // attach another user to the personal team
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        // update the other user's role from a livewire component
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                             ->set('managingRoleFor', $otherUser)
                             ->set('currentRole', 'editor')
                             ->call('updateRole');

        // assert that the other user's role was updated
        $this->assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'editor'
        ));
    }

    /**
     * Test that only the team owner can update team member roles.
     *
     * @return void
     */
    public function test_only_team_owner_can_update_team_member_roles(): void
    {
        // require laravel exception handling for this test -- otherwise the test will fail with the following error:
        // Illuminate\Auth\Access\AuthorizationException: This action is unauthorized.
        $this->withExceptionHandling();

        // create a user with a personal team
        $user = User::factory()->withPersonalTeam()->create();

        // attach another user to the personal team
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        // act as the other user
        $this->actingAs($otherUser);

        // attempt to update the other user's role as the other user from a livewire component and assert that it fails
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                             ->set('managingRoleFor', $otherUser)
                             ->set('currentRole', 'editor')
                             ->call('updateRole')
                             ->assertStatus(403);

        // assert that the other user's role was not updated
        $this->assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'admin'
        ));
    }
}
