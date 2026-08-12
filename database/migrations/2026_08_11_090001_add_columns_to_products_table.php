<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('min_stock')->default(0);
            $table->dropColumn('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->default(0);
            $table->dropColumn(['purchase_price', 'selling_price', 'description', 'image', 'min_stock']);
        });
    }
};
