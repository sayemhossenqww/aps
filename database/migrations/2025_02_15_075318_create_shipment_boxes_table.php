<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shipment_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null'); // Assuming customers table exists
            $table->string('box_name');
            $table->string('box_barcode')->unique();
            $table->decimal('box_weight', 10, 2);
            $table->decimal('box_price', 10, 2);
            $table->decimal('box_shipment_charge', 10, 2)->nullable();
            $table->date('box_shipping_date')->nullable();
            $table->date('box_delivery_date')->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_boxes');
    }
};
