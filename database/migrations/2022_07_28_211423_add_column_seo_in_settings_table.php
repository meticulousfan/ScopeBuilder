<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSeoInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('metaTitle')->nullable();
            $table->text('metaDescription')->nullable();
            $table->string('metaImage')->nullable();
            $table->text('referralMetaTitle')->nullable();
            $table->text('referralMetaDesc')->nullable();
            $table->boolean('referralMetaImage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('wa_id');
        });
    }
}
