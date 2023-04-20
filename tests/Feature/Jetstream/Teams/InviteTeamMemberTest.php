<?php

namespace Tests\Feature\Jetstream\Teams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Laravel\Jetstream\Mail\TeamInvitation;
use Livewire\Livewire;
use Tests\TestCase;

class InviteTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skip test if team invitations are not enabled within Jetstream config
     *
     * @return void
     */
    private function skip_test_if_jetstream_team_invitations_are_not_enabled(): void
    {
        if (!Features::sendsTeamInvitations()) {
            $this->markTestSkipped('Team invitations not enabled.');
        }
    }

    /**
     * Test a team member can be invited to a team.
     *
     * @return void
     */
    public function test_team_members_can_be_invited_to_team(): void
    {
        $this->skip_test_if_jetstream_team_invitations_are_not_enabled();

        // fake the mailer event
        Mail::fake();

        $user = $this->create_and_act_as_user_with_personal_team();

        // invite a team member by providing an email address and role from a livewire component
        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                ->set('addTeamMemberForm', [
                    'email' => 'test@example.com',
                    'role'  => 'admin',
                ])->call('addTeamMember');

        // assert that the team invitation was sent
        Mail::assertSent(TeamInvitation::class);

        // assert that the team invitation was created
        $this->assertCount(1, $user->currentTeam->fresh()->teamInvitations);
    }

    /**
     * Test a team member invitation can be cancelled.
     *
     * @return void
     */
    public function test_team_member_invitations_can_be_cancelled(): void
    {
        $this->skip_test_if_jetstream_team_invitations_are_not_enabled();

        // fake the mailer event
        Mail::fake();

        $user = $this->create_and_act_as_user_with_personal_team();

        // Add the team member to the team
        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                             ->set('addTeamMemberForm', [
                                 'email' => 'test@example.com',
                                 'role'  => 'admin',
                             ])->call('addTeamMember');

        // Get the team invitation ID
        $invitationId = $user->currentTeam->fresh()->teamInvitations->first()->id;

        // Cancel the team invitation
        $component->call('cancelTeamInvitation', $invitationId);

        // assert that the team invitation was cancelled
        $this->assertCount(0, $user->currentTeam->fresh()->teamInvitations);
    }
}
