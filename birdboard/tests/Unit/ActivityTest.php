<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use App\User;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function activity_has_a_user()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $this->assertInstanceOf(User::class, $project->activity->first()->user);
        $this->assertEquals($project->activity->first()->user->id, auth()->user()->id);
    }
}
