<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessReasonRelationToTranslationAccessReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translation_access_reasons', function (Blueprint $table) {
            $table->unsignedBigInteger('access_reason_id');

            $table->foreign('access_reason_id')
                ->references('id')
                ->on('access_reasons')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translation_access_reasons', function (Blueprint $table) {
            $table->dropForeign(['access_reason_id']);
        });

        Schema::table('translation_access_reasons', function (Blueprint $table) {
            $table->dropColumn('access_reason_id');
        });
    }
}
