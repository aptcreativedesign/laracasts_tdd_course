<?php

namespace Tests\Feature\Jetstream\Teams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that teams can be created by a user.
     *
     * @return void
     */
    public function test_teams_can_be_created(): void
    {
        $user = $this->create_and_act_as_user_with_personal_team();

        // create a new team for the user within a livewire component
        Livewire::test(CreateTeamForm::class)
                ->set(['state' => ['name' => 'Test Team']])
                ->call('createTeam');

        // assert that the team was created
        $this->assertCount(2, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->fresh()->ownedTeams()->latest('id')->first()->name);
    }
}
