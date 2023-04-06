<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_projects', function (Blueprint $table) {
            $table->efficientUuid('uuid')->index()->nullable();
            $table->id();
            $table->enum('project_type', ['new', 'existing'])->default('new');
            $table->string('name');
            $table->longText('description');
            $table->string('code_repository_link', 500)->nullable();
            $table->longText('example_projects');
            $table->integer('example_projects_count');
            $table->enum('type', ['mobile', 'web', 'both'])->default('mobile');
            $table->text('where_add')->nullable();

            $table->longText('web_frameworks')->nullable();
            $table->longText('mobile_frameworks')->nullable();

            $table->boolean('is_draft')->default(0);
            $table->integer('left_step');
            $table->enum('tech_type', ['mobile', 'web', 'both'])->default('mobile');
            $table->enum('both_same_functionality', ['0', '1'])->default('1');
            $table->unsignedInteger('project_number')->default(1);

            $table->string('budget', 100)->nullable();
            $table->enum('contact_developer', ['0', '1'])->default('0');

            $table->unsignedBigInteger('editing_user_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->efficientUuid('user_uuid')->index()->nullable();
            $table->unsignedInteger('assigned_to_user_id')->nullable();
            $table->string('mockup')->nullable();
            $table->string('mockup_url')->nullable();
            
            $table->timestamps();
        });

        Schema::create('client_project_details', function (Blueprint $table_details){
            $table_details->id();
            $table_details->foreignId('project_id')->constrained('client_projects')->onDelete('cascade');
            $table_details->enum('type', ['mobile', 'web', 'both'])->default('mobile');
            $table_details->string('identifier');
            $table_details->text('data');

            $table_details->unique(['type', 'identifier']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_project_details');
        Schema::dropIfExists('client_projects');
    }
}
