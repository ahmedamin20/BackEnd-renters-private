<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Helpers\OrderHelper;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->onDelete('set null');

            $table->foreignId('delivery_man_id')
                ->nullable()
                ->constrained('delivery_men')
                ->cascadeOnUpdate()
                ->onDelete('set null');

            $table->enum('status', array_keys(OrderHelper::availableStates()));
            $table->json('order_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
