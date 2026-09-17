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
        Schema::table('products', function (Blueprint $table) {
            $table->string('location')->nullable()->after('status');
            $table->integer('bedrooms')->default(0)->after('location');
            $table->integer('bathrooms')->default(0)->after('bedrooms');
            $table->integer('floors')->default(1)->after('bathrooms');
            $table->integer('rooms')->default(0)->after('floors');
            $table->decimal('land_area', 10, 2)->nullable()->after('rooms');
            $table->decimal('building_area', 10, 2)->nullable()->after('land_area');
            $table->decimal('price', 15, 2)->nullable()->after('building_area');
            $table->text('description')->nullable()->after('price');
            $table->text('features')->nullable()->after('description'); // JSON array of features
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'bedrooms',
                'bathrooms', 
                'floors',
                'rooms',
                'land_area',
                'building_area',
                'price',
                'description',
                'features'
            ]);
        });
    }
};