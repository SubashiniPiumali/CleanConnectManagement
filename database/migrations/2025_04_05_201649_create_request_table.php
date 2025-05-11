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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->unsignedBigInteger('team_member_id')->nullable();
            $table->string('contact_number');
            $table->text('address');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('status', ['pending', 'assigned', 'canceled'])->default('pending');
            $table->time('work_shift_from');
            $table->time('work_shift_to');
            $table->date('start_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('team_member_id')->references('id')->on('team_members')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
};
