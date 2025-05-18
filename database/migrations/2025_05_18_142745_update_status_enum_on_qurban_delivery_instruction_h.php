<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE qurban_delivery_instruction_h 
            MODIFY COLUMN status ENUM('scheduled', 'ready_to_deliver', 'in_delivery', 'delivered') 
            DEFAULT 'scheduled'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE qurban_delivery_instruction_h 
            MODIFY COLUMN status ENUM('scheduled', 'in_delivery', 'delivered') 
            DEFAULT 'scheduled'");
    }
};
