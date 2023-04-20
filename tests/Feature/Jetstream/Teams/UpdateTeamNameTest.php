<?php

namespace Tests\Feature\Jetstream\Teams;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateTeamNameForm;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTeamNameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the team name can be updated from a livewire component.
     *
     * @return void
     */
    public function test_team_names_can_be_updated(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // update the team name from a livewire component
        Livewire::test(UpdateTeamNameForm::class, ['team' => $user->currentTeam])
                ->set(['state' => ['name' => 'Test Team']])
                ->call('updateTeamName');

        // assert that there is only one team and that the team name was updated
        $this->assertCount(1, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->currentTeam->fresh()->name);
    }

    // TODO -- Jetstream allows any user to update the name of any team. This is a bug in Jetstream.
}
