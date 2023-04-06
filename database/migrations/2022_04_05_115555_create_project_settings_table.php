<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_settings', function (Blueprint $table) {
            $table->id('setting_id');
            $table->string('setting_group', 100);
            $table->string('setting_key', 100);
            $table->text('setting_value');
            $table->enum('setting_is_serialized', ['0', '1'])->default('0');
            $table->enum('setting_status', ['0', '1'])->default('1');
            $table->timestamp('setting_timestamp', $precision = 0)->useCurrent();

            $table->unique(['setting_group', 'setting_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_settings');
    }
}
