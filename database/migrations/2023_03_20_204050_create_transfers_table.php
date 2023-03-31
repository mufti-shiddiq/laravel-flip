<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('external_id');
            $table->string('bank_code');
            $table->string('account_number');
            $table->string('recipient_name');
            $table->string('remark')->nullable();
            $table->string('sender_bank')->nullable();
            $table->integer('amount');
            $table->integer('fee');
            $table->string('status');
            $table->dateTime('time_served', $precision = 0)->nullable();
            $table->string('reason')->nullable();
            $table->text('receipt')->nullable();
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
        Schema::dropIfExists('transfers');
    }
};
