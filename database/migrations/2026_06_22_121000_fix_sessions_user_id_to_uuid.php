<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if user_id column exists and what type it is
        $columns = DB::select("SHOW COLUMNS FROM sessions LIKE 'user_id'");
        if (!empty($columns)) {
            $columnType = $columns[0]->Type;
            
            // If it's not already UUID, replace it
            if (strpos($columnType, 'char') === false || $columns[0]->Length !== 36) {
                Schema::table('sessions', function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });
                
                Schema::table('sessions', function (Blueprint $table) {
                    $table->uuid('user_id')->nullable()->index()->after('id');
                });
            }
        } else {
            Schema::table('sessions', function (Blueprint $table) {
                $table->uuid('user_id')->nullable()->index()->after('id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index();
        });
    }
};