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
        Schema::create('pay_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->index();
			$table->foreignId('user_id')->index();
            $table->string('external_id');
            $table->integer('amount_paid_in_cents');
            $table->time('time_worked');
            $table->integer('hourly_rate_in_cents');
            $table->date('paid_at');
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
        Schema::dropIfExists('pay_items');
    }
};
