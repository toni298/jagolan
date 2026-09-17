<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('products', 'product_type_id')) {
            Schema::table('products', function (Blueprint $table) {
                try { $table->dropForeign(['product_type_id']); } catch (\Throwable $e) {}
                $table->dropColumn('product_type_id');
            });
        }

        Schema::dropIfExists('product_types');
    }

    public function down(): void
    {
        // Irreversible: product types feature removed.
    }
};
