<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->efficientUuid('uuid')->nullable(true)->index();
            $table->integer('user_id')->nullable(true);
            $table->integer('type')->default(0)->nullable(true);//0- project,1-call
            $table->char('project_id', 16)->charset('binary');
            $table->char('bbb_meeting_id', 16)->charset('binary')->nullable(true);
            $table->unsignedDecimal('amount',8,2)->default(0)->nullable();
            $table->string('stripe_id')->nullable(true);
            $table->integer('status')->default(0)->nullable(true);//0- pending, 1-canceled, 2-success
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
        Schema::dropIfExists('transactions');
    }
}
