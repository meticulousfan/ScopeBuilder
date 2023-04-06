<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id('country_id');
            $table->string('country_iso', 45)->nullable();
            $table->string('country_iso3', 45)->nullable();
            $table->string('country_name', 45)->nullable();
            $table->string('country_currency_code', 45)->nullable();
            $table->string('country_currency_name', 45)->nullable();
            $table->enum('country_status', ['0', '1'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
