<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;

class TriggerActivityTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();
        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity->first()->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    /** @test */
    public function creating_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->addTask('Some task');
        $this->assertCount(2, $project->activity);
        $this->assertEquals('task_created', $project->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $project->tasks->first()->delete();
        $this->assertCount(3, $project->activity);
        $this->assertEquals('task_deleted', $project->activity->last()->description);
    }

    /** @test */
    public function marking_a_task_complete()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);
        $this->assertCount(3, $project->activity);
        $this->assertEquals('task_completed', $project->activity->last()->description);
    }

    /** @test */
    public function marking_a_task_incomplete()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create(['completed' => true]);
        $project->tasks->first()->incomplete();
        $this->assertCount(3, $project->activity);
        $this->assertEquals('task_incomplete', $project->activity->last()->description);
    }
}
