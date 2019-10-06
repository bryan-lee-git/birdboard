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
    public function creating_a_project_generates_activity()
    {
        $project = ProjectFactory::create();
        $this->assertCount(1, $project->activities);
        $this->assertEquals('created', $project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project_generates_activity()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activities);
        $this->assertEquals('updated', $project->activities->last()->description);
    }
}
