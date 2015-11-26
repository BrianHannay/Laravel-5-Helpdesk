<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status');
            $table->integer('priority');
            $table->dateTime('date_created');
            $table->dateTime('date_closed');
            $table->integer('placed_by')->unsigned();
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->string("subject", 63);
            $table->foreign('placed_by')->references('id')->on('users');
            $table->foreign('assigned_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket');
    }
}
