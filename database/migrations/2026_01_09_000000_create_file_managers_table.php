<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_managers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('original_name');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('file_type');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index('file_type');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_managers');
    }
};