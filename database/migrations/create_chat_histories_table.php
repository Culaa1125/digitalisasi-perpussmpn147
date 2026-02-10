<?php
// // database/migrations/xxxx_create_chat_histories_table.php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('chat_histories', function (Blueprint $table) {
//             $table->id();
//             $table->string('nis');
//             $table->string('session_id');
//             $table->text('message');
//             $table->text('response');
//             $table->timestamps();

//             $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
//             $table->index(['nis', 'session_id']);
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('chat_histories');
//     }
// };