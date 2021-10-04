<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Ecommerce\App\Enums\StatusEnum;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('content')->nullable();
            $table->string('slug')->index();
            $table->decimal('discount_price')->nullable();
            $table->decimal('original_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('model_no')->nullable();
            $table->string('barcode')->nullable();
            $table->string('sku')->nullable();
            $table->string('status', 32)->default(StatusEnum::PREVIEW())->index();
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
