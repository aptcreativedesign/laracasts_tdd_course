<?php

namespace Tests\Feature\Jetstream;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the current profile information is available on the livewire component.
     *
     * @return void
     */
    public function test_current_profile_information_is_available(): void
    {
        $user = $this->create_and_act_as_user();

        // get the current profile information from a livewire component
        $component = Livewire::test(UpdateProfileInformationForm::class);

        // assert that the current profile information is available on the livewire component
        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->email, $component->state['email']);
    }

    /**
     * Test that the user's profile information can be updated from a livewire component.
     *
     * @return void
     */
    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->create_and_act_as_user();

        // test that the user's profile information can be updated from a livewire component
        Livewire::test(UpdateProfileInformationForm::class)
                ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
                ->call('updateProfileInformation');

        // assert that the user's profile information was updated
        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}
