<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('shipment_boxes', function (Blueprint $table) {
            $table->string('zone')->nullable()->after('customer_id'); // Adding Zone field
            $table->foreignId('delivery_id')->nullable()->after('zone')->constrained('deliveries')->onDelete('set null'); // Adding Delivery foreign key
        });
    }

    public function down()
    {
        Schema::table('shipment_boxes', function (Blueprint $table) {
            $table->dropColumn('zone');
            $table->dropForeign(['delivery_id']);
            $table->dropColumn('delivery_id');
        });
    }
};
