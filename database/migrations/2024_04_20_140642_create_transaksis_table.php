<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamp('tanggal_pengembalian')->nullable();
            $table->bigInteger('harga');
            $table->bigInteger('qty');
            $table->string('status');
            $table->unsignedBigInteger("user_id")->nullable(false);
            $table->unsignedBigInteger("car_id")->nullable(false);

            $table->foreign("user_id")->on("users")->references("id");
            $table->foreign("car_id")->on("cars")->references("id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
