<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->efficientUuid('uuid')->nullable()->index();
            $table->id();
            $table->string('name');
            $table->string('username')->nullable(true);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_email_verified')->default(0);
            $table->string('password');
            $table->string('paypal_email')->nullable();
            $table->string('payoneer_email')->nullable();
            $table->string('referral_token')->unique()->nullable();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->foreign('referrer_id')->references('id')->on('users');

            $table->rememberToken();
            $table->integer('country_id')->default(840)->nullable();
            $table->string('timezone', 100)->nullable();
            $table->string('company', 200)->nullable();
            $table->string('language', 2)->default('en')->nullable();
            $table->enum('can_take_calls', ['0', '1'])->default('1');
            $table->enum('can_start_calls', ['0', '1'])->default('1');
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
        Schema::dropIfExists('users');
    }
}
