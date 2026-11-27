<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('categories', 'show_on_home')) {
                $table->boolean('show_on_home')->default(true)->after('is_featured');
            }
        });

        // Dedicated subcategories table for database completeness
        if (!Schema::hasTable('subcategories')) {
            Schema::create('subcategories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->boolean('show_on_home')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategories');
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'show_on_home')) {
                $table->dropColumn('show_on_home');
            }
        });
    }
};
