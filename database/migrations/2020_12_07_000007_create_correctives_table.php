<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectivesTable extends Migration
{
    public function up()
    {
        Schema::create('correctives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('finding_date')->nullable();
            $table->longText('finding')->nullable();
            $table->string('action')->nullable();
            $table->date('target_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
