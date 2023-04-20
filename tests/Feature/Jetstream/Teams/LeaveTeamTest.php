<?php

namespace Tests\Feature\Jetstream\Teams;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

class LeaveTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can leave a team. Not the user who owns the team.
     *
     * @return void
     */
    public function test_users_can_leave_teams(): void
    {
        // create a user with a personal team
        $user = User::factory()->withPersonalTeam()->create();

        // attach another user to the personal team
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        // act as the other user
        $this->actingAs($otherUser);

        // leave the team from a livewire component
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                ->call('leaveTeam');

        // assert that the user was removed from the team
        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    /**
     * Test that a user can't leave a team they own.
     *
     * @return void
     */
    public function test_team_owners_cant_leave_their_own_team(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // as the team owner, attempt to leave the team
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                ->call('leaveTeam')
                ->assertHasErrors(['team']);

        // assert that the team owner was not removed from the team
        $this->assertNotNull($user->currentTeam->fresh());
    }
}
