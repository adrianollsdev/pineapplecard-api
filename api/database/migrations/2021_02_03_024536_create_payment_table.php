<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
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
            $table->dateTime('created_at', $precision = 0);
            $table->char('type', 1);
            $table->string('establishment_name', 100);
            $table->decimal('establishment_latitude', $precision = 8, $scale = 6);
            $table->decimal('establishment_longitude', $precision = 9, $scale = 6);
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->foreignId('establishment_category_id')->constrained('establishment_category');
            $table->foreignId('user_id')->constrained('profile');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
}
