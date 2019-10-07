<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use App\Task;

class TriggerActivityTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();
        $this->assertCount(1, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('project_created', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $originalTitle = $project->title;
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activity);
        tap($project->activity->last(), function ($activity) use ($originalTitle) {
            $this->assertEquals('project_updated', $activity->description);
            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'changed']
            ];
            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    public function creating_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->addTask('Some task');
        $this->assertCount(2, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_created', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some task', $activity->subject->body);
        });
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $project->tasks->first()->delete();
        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_deleted', $activity->description);
        });
    }

    /** @test */
    public function marking_a_task_complete()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $project->tasks->first()->complete();
        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_completed', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertTrue($activity->subject->completed);
        });
    }

    /** @test */
    public function marking_a_task_incomplete()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create(['completed' => true]);
        $project->tasks->first()->incomplete();
        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_incomplete', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertFalse($activity->subject->completed);
        });
    }
}
