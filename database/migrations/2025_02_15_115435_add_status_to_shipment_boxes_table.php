<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('shipment_boxes', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('box_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('shipment_boxes', function (Blueprint $table) {
            $table->dropColumn(['status', 'delivered_at']);
        });
    }
};
