<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;

class ActivityFeedTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function creating_a_project_records_activity()
    {
        $project = ProjectFactory::create();
        $this->assertCount(1, $project->activities);
        $this->assertEquals('created', $project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project_records_activity()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activities);
        $this->assertEquals('updated', $project->activities->last()->description);
    }

    /** @test */
    public function creating_a_new_task_records_project_activity()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->addTask('Some task');
        $this->assertCount(2, $project->activities);
        $this->assertEquals('task_created', $project->activities->last()->description);
    }

    /** @test */
    public function completing_a_task_records_project_activity()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);
        $this->assertCount(3, $project->activities);
        $this->assertEquals('task_completed', $project->activities->last()->description);
    }
}
