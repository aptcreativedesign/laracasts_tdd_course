<?php

namespace Tests\Feature\Jetstream;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Http\Livewire\UpdatePasswordForm;
use Livewire\Livewire;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the user's password can be updated from a livewire component.
     *
     * @return void
     */
    public function test_password_can_be_updated(): void
    {
        $user = $this->create_and_act_as_user();

        // test that the user's password can be updated from a livewire component
        Livewire::test(UpdatePasswordForm::class)
                ->set('state', [
                    'current_password'      => 'password',
                    'password'              => 'new-password',
                    'password_confirmation' => 'new-password',
                ])
                ->call('updatePassword');

        // assert that the user's password was updated
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    /**
     * Test that the current password must be correct when updating the user's password.
     *
     * @return void
     */
    public function test_current_password_must_be_correct(): void
    {
        $user = $this->create_and_act_as_user();

        // test that when the current password is wrong, the user's password is not updated and an error is shown
        Livewire::test(UpdatePasswordForm::class)
                ->set('state', [
                    'current_password'      => 'wrong-password',
                    'password'              => 'new-password',
                    'password_confirmation' => 'new-password',
                ])
                ->call('updatePassword')
                ->assertHasErrors(['current_password']);

        // assert that the user's password was not updated
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    /**
     * Test that the new passwords must match when updating the user's password.
     *
     * @return void
     */
    public function test_new_passwords_must_match(): void
    {
        $user = $this->create_and_act_as_user();

        // test that when the new passwords do not match, the user's password is not updated and an error is shown
        Livewire::test(UpdatePasswordForm::class)
                ->set('state', [
                    'current_password'      => 'password',
                    'password'              => 'new-password',
                    'password_confirmation' => 'wrong-password',
                ])
                ->call('updatePassword')
                ->assertHasErrors(['password']);

        // assert that the user's password was not updated
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
