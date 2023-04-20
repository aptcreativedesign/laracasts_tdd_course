<?php

namespace Tests\Feature\Jetstream\Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\DeleteTeamForm;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a team can be deleted by a user.
     *
     * @return void
     */
    public function test_teams_can_be_deleted(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // create a new team owned by the user - not a personal team
        $user->ownedTeams()->save($team = Team::factory()->make([
            'personal_team' => false,
        ]));

        // attach another user to the team and set a role for the user on the team
        $team->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'test-role']
        );

        // delete the team from a livewire component
        Livewire::test(DeleteTeamForm::class, ['team' => $team->fresh()])
                ->call('deleteTeam');

        // assert that the team was deleted
        $this->assertNull($team->fresh());
        $this->assertCount(0, $otherUser->fresh()->teams);
    }

    public function test_personal_teams_cant_be_deleted(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // attempt to delete the personal team from a livewire component
        Livewire::test(DeleteTeamForm::class, ['team' => $user->currentTeam])
                ->call('deleteTeam')
                ->assertHasErrors(['team']);

        // assert that the personal team was not deleted
        $this->assertNotNull($user->currentTeam->fresh());
    }
}
