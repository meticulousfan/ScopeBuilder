<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsNoteInPayoutRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->string('note')->nullable(true);
            $table->unsignedDecimal('amount',8,2)->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
}
