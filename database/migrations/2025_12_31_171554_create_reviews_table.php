<?php
// database/migrations/xxxx_xx_xx_create_reviews_table.php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('reviews', function (Blueprint $table) {
//             $table->id('review_id');
//             $table->foreignId('konten_id')->constrained('konten', 'konten_id')->onDelete('cascade');
//             $table->string('nis')->nullable();
//             $table->foreign('nis')->references('nis')->on('siswa')->onDelete('set null');
//             $table->integer('rating')->default(5); // 1-5
//             $table->text('comment')->nullable();
//             $table->timestamps();
            
//             $table->index(['konten_id', 'created_at']);
//         });
        
//         // Add rating columns to konten table
//         Schema::table('konten', function (Blueprint $table) {
//             $table->decimal('average_rating', 3, 2)->default(0)->after('view_count');
//             $table->integer('total_reviews')->default(0)->after('average_rating');
//         });
//     }

//     public function down()
//     {
//         Schema::table('konten', function (Blueprint $table) {
//             $table->dropColumn(['average_rating', 'total_reviews']);
//         });
        
//         Schema::dropIfExists('reviews');
//     }
// };