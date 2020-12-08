<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCorrectivesTable extends Migration
{
    public function up()
    {
        Schema::table('correctives', function (Blueprint $table) {
            $table->unsignedBigInteger('sources_id')->nullable();
            $table->foreign('sources_id', 'sources_fk_2748422')->references('id')->on('sources');
            $table->unsignedBigInteger('tanggung_jawab_id')->nullable();
            $table->foreign('tanggung_jawab_id', 'tanggung_jawab_fk_2748423')->references('id')->on('users');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id', 'status_fk_2748425')->references('id')->on('statuses');
        });
    }
}
