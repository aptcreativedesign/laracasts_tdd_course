<?php

namespace Tests\Feature\Jetstream\Teams;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

class RemoveTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a team member can be removed from a team by the owner.
     *
     * @return void
     */
    public function test_team_members_can_be_removed_from_teams(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // attach another user to the personal team
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        // remove the other user from the team from a livewire component
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                             ->set('teamMemberIdBeingRemoved', $otherUser->id)
                             ->call('removeTeamMember');

        // assert that the other user was removed from the team
        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    /**
     * Test a user who is not the team owner cannot remove team members.
     *
     * @return void
     */
    public function test_only_team_owner_can_remove_team_members(): void
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

        // attempt from a livewire component to remove a team member from the team as the other user (not the owner)
        // assert that the other user is forbidden from removing team members
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                             ->set('teamMemberIdBeingRemoved', $user->id)
                             ->call('removeTeamMember')
                             ->assertStatus(403);
    }
}
