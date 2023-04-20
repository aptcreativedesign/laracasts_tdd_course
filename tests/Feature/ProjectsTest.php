<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
//    use withFaker, RefreshDatabase;
//
//    /**
//     * Testing a user can create a project
//     *
//     * @return void
//     */
//    public function test_a_user_can_create_a_project(): void
//    {
//        $attributes = [
//            'title'       => $this->faker->sentence,
//            'description' => $this->faker->paragraph,
//        ];
//
//        // post to the add a new projects endpoint
//        // then assert that the user is redirected to the projects index page
//        $this->post('/projects', $attributes)->assertRedirect('/projects');
//
//        // check if the project is inserted into the database
//        $this->assertDatabaseHas('projects', $attributes);
//
//        // check if the user sees the project on the projects index page
//        $this->get('/projects')->assertSee($attributes['title']);
//    }
//
//    /**
//     * Testing a user can view a project
//     *
//     * @return void
//     */
//    public function test_a_user_can_view_a_project()
//    {
//        // create a project
//        $project = Project::factory()->create();
//
//        // get the project show page
//        $this->get($project->path())
//             ->assertSee($project->title)
//             ->assertSee($project->description);
//    }
//
//    /**
//     * A project requires a title
//     *
//     * @return void
//     */
//    public function test_a_project_requires_a_title(): void
//    {
//        // use the factory to create a project without a title
//        // raw() returns an array of attributes
//        $attributes = Project::factory()->raw(['title' => '']);
//
//        // post to the add a new projects endpoint
//        // then assert that the user was given a validation error
//        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
//    }
//
//    /**
//     * A project requires a description
//     *
//     * @return void
//     */
//    public function test_a_project_requires_a_description(): void
//    {
//        // use the factory to create a project without a description
//        // raw() returns an array of attributes
//        $attributes = Project::factory()->raw(['description' => '']);
//
//        // post to the add a new projects endpoint
//        // then assert that the user was given a validation error
//        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
//    }
//
//    public function test_a_project_requires_an_owner()
//    {
//        $attributes = Project::factory()->raw();
//
//        // post to the add a new projects endpoint
//        // then assert that the user was given a validation error
//        $this->post('/projects', $attributes)->assertSessionHasErrors('owner');
//
//    }
}

