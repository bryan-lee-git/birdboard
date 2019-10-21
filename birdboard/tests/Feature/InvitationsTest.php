<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Facades\Tests\Setup\ProjectFactory;

class InvitationsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function non_owners_may_not_invite_users()
    {
        $project = ProjectFactory::create();
        $this->signIn();
        $userToInvite = factory(User::class)->create();
        $this->post(action('ProjectInvitationsController@store', $project), [
            'email' => $userToInvite->email
        ])->assertStatus(403);
    }

    /** @test */
    public function a_project_owner_can_invite_a_user()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $userToInvite = factory(User::class)->create();
        $this->post(action('ProjectInvitationsController@store', $project), [
            'email' => $userToInvite->email
        ])->assertRedirect($project->path());
        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function invited_users_may_update_project_details()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $project->invite($newUser = factory(\App\User::class)->create());
        $this->signIn($newUser);
        $this->post(action('ProjectTasksController@store', $project), $task = [
            'body' => 'Foo task'
        ]);
        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $this->post(action('ProjectInvitationsController@store', $project), [
            'email' => 'notauser@email.com'
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a birdboard account.\''
        ]);
    }
}
