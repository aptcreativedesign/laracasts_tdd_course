<?php

namespace Tests\Feature\Jetstream;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\LogoutOtherBrowserSessionsForm;
use Livewire\Livewire;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a user can log out of other browser sessions.
     *
     * @return void
     */
    public function test_other_browser_sessions_can_be_logged_out(): void
    {
        $this->create_and_act_as_user();

        // log out other browser sessions from a livewire component
        Livewire::test(LogoutOtherBrowserSessionsForm::class)
                ->set('password', 'password')
                ->call('logoutOtherBrowserSessions')
                ->assertSuccessful();
    }
}
