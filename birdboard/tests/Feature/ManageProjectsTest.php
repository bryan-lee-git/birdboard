<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_projects()
    {
        $project = ProjectFactory::create();
        $this->post('/projects', $project->toArray())
            ->assertRedirect('login');
        $this->get('/projects')
            ->assertRedirect('login');
        $this->get('/projects/create')
            ->assertRedirect('login');
        $this->get($project->path())
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_project()
    {
        $this->signIn();
        $this->get('/projects/create')
            ->assertStatus(200);
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General notes here.'
        ];
        $response = $this->post('/projects', $attributes);
        $project = Project::where($attributes)
            ->first();
        $response->assertRedirect($project->path());
        $this->assertDatabaseHas('projects', $attributes);
        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $this->patch($project->path(), [
            'notes' => 'changed'
        ])->assertRedirect($project->path());
        $this->assertDatabaseHas('projects', ['notes' => 'changed']);
    }

    /** @test */
    public function user_can_view_their_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->create();
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();
        $project = ProjectFactory::create();
        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();
        $project = ProjectFactory::create();
        $this->patch($project->path(), [])
            ->assertStatus(403);
    }

    /** @test */
    public function project_requires_a_title()
    {
        $this->signIn();
        $attributes = factory('App\Project')
            ->raw(['title' => '']);
        $this->post('/projects', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function project_requires_a_description()
    {
        $this->signIn();
        $attributes = factory('App\Project')
            ->raw(['description' => '']);
        $this->post('/projects', $attributes)
            ->assertSessionHasErrors('description');
    }
}
