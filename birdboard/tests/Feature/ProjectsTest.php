<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_create_projects()
    {
        $attributes = factory('App\Project')->raw(['ownerId' => null]);
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_may_not_view_projects()
    {
        $this->get('/projects')->assertRedirect('/login');
    }

    /** @test */
    public function guests_cannot_view_single_project()
    {
        $project = factory('App\Project')->create();
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function user_can_create_project()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', $attributes);
        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function user_can_view_their_project()
    {
        $this->actingAs(factory('App\User')->create());

        $project = factory('App\Project')->create(['ownerId' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->actingAs(factory('App\User')->create());

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function project_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function project_requires_a_description()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
