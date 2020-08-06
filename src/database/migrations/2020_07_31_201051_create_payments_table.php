<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('transaction_id',32)->nullable();
            $table->integer('user_id');
            $table->float('amount')->nullable();
            $table->string('currency_code',3)->nullable();
            $table->string('method',32)->nullable();
            $table->string('name',32)->nullable();
            $table->text('comment')->nullable();
            $table->string('status', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
