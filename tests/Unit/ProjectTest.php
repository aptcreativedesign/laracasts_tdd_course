<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_a_path(): void
    {
        // create a project
        $project = Project::factory()->create();

        // assert that the path is the same as the concatenation of /project/id
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }
}
