<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousClientProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymous_client_projects', function (Blueprint $table) {

            $table->efficientUuid('uuid')->nullable()->index();
            $table->id();
            $table->enum('project_type', ['new', 'existing'])->default('new');
            $table->string('name');
            $table->longText('description');
            $table->string('code_repository_link', 500)->nullable();
            $table->string('mockup')->nullable();
            $table->string('mockup_url')->nullable();
            $table->longText('example_projects');
            $table->integer('example_projects_count');
            $table->enum('type', ['mobile', 'web', 'both'])->default('mobile');
            $table->enum('both_same_functionality', ['0', '1'])->default('1');
            $table->text('where_add')->nullable();
            $table->longText('web_frameworks')->nullable();
            $table->longText('mobile_frameworks')->nullable();
            $table->string('budget', 100)->nullable();
            $table->enum('contact_developer', ['0', '1'])->default('0');
            $table->boolean('is_draft')->default(0);
            $table->integer('left_step');
            $table->enum('tech_type', ['mobile', 'web', 'both'])->default('mobile');
            $table->unsignedInteger('project_number')->default(1);
            $table->unsignedBigInteger('editing_user_id')->nullable();
            $table->unsignedInteger('assigned_to_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anonymous_client_projects');
    }
}
