<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerIdColumnToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger('ownerId');
            $table->foreign('ownerId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('projects', 'ownerId')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropForeign('projects_ownerid_foreign');
                $table->dropColumn('ownerId');
            });
        }
    }
}
