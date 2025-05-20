<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'ready_to_deliver', 'in_delivery', 'delivered'])->default('scheduled');
            $table->string('receipt_photo')->nullable()->after('status');
            $table->timestamp('receipt_at')->nullable()->after('receipt_photo');
        });
    }

    public function down()
    {
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->dropColumn(['status', 'receipt_photo' , 'receipt_at']);
        });
    }

};
