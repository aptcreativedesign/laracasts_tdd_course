<?php

namespace Tests\Feature\Jetstream;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skip test if account deletion is not enabled within Jetstream config
     *
     * @return void
     */
    private function skip_test_if_jetstream_account_deletion_is_not_enabled(): void
    {
        if (!Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped('Account deletion is not enabled.');
        }
    }

    /**
     * Test a user account can be deleted.
     * Skip this test if account deletion is not enabled within Jetstream config
     *
     * @return void
     */
    public function test_user_accounts_can_be_deleted(): void
    {
        $this->skip_test_if_jetstream_account_deletion_is_not_enabled();

        $user = $this->create_and_act_as_user();

        // delete the user account by providing the correct password from a livewire component
        Livewire::test(DeleteUserForm::class)
                ->set('password', 'password')
                ->call('deleteUser');

        // assert that the user account was deleted
        $this->assertNull($user->fresh());
    }

    /**
     * Test a user account cannot be deleted without providing the correct password.
     * Skip this test if account deletion is not enabled within Jetstream config
     *
     * @return void
     */
    public function test_correct_password_must_be_provided_before_account_can_be_deleted(): void
    {
        $this->skip_test_if_jetstream_account_deletion_is_not_enabled();

        $user = $this->create_and_act_as_user();

        // attempt to delete the user account with the wrong password from a livewire component
        Livewire::test(DeleteUserForm::class)
                ->set('password', 'wrong-password')
                ->call('deleteUser')
                ->assertHasErrors(['password']);

        // assert that the user account was not deleted
        $this->assertNotNull($user->fresh());
    }
}
