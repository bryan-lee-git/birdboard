<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->create();
        $this->post($project->path() . '/tasks', ['body' => 'Test task.']);
        $this->get($project->path())
            ->assertSee('Test task.');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->create();
        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        $project = ProjectFactory::withTasks(1)
            ->create();
        $this->post($project->path() . '/tasks', [
            'body' => 'Test task.'
        ])->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => 'Test task.']);
    }

    /** @test */
    public function only_owner_of_a_project_may_update_tasks()
    {
        $this->signIn();
        $project = ProjectFactory::withTasks(1)
            ->create();
        $this->patch($project->tasks->first()->path(), ['body' => 'changed'])
            ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = ProjectFactory::create();
        $this->post($project->path() . '/tasks')
            ->assertRedirect('login');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed']);
        $project->tasks->first()->complete();

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }
}
