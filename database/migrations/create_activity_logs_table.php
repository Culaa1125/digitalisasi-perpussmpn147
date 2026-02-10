<?php
// database/migrations/xxxx_create_activity_logs_table.php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('activity_logs', function (Blueprint $table) {
//             $table->id();
//             $table->string('nis');
//             $table->string('activity_type'); // login, logout, read_book, watch_video
//             $table->text('description')->nullable();
//             $table->unsignedBigInteger('konten_id')->nullable(); // untuk read_book dan watch_video
//             $table->string('ip_address')->nullable();
//             $table->text('user_agent')->nullable();
//             $table->timestamps();

//             $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
//             $table->foreign('konten_id')->references('konten_id')->on('konten')->onDelete('set null');
//             $table->index(['nis', 'created_at']);
//             $table->index('activity_type');
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('activity_logs');
//     }
// };