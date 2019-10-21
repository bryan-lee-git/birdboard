<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use App\User;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory('App\User')->create();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $john = $this->signIn();
        $sally = factory(User::class)->create();
        $nick = factory(User::class)->create();

        ProjectFactory::ownedBy($john)->create();
        $this->assertCount(1, $john->accessibleProjects());

        $project = ProjectFactory::ownedBy($sally)->create();
        $project->invite($nick);

        $this->assertCount(1, $john->accessibleProjects());

        $project->invite($john);

        $this->assertCount(2, $john->accessibleProjects());
    }
}
