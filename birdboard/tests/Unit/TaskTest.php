<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Task;
use App\Project;

class TaskTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function task_has_a_path()
    {
        $task = factory(Task::class)->create();
        $this->assertEquals("/projects/{$task->projectId}/tasks/{$task->id}", $task->path());
    }

    /** @test */
    public function task_belongs_to_a_project()
    {
        $task = factory(Task::class)->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }
}
