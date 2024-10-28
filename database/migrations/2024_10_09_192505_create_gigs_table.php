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
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->text('description');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('number_of_positions');
            $table->double('pay_per_hour');
            $table->boolean('status')->default(true);
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};
