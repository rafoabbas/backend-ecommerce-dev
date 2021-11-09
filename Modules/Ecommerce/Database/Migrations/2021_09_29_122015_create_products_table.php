<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Ecommerce\App\Enums\StatusEnum;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
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
            $table->boolean('approved')->default(false);
            $table->boolean('auto_approve')->default(false);
            $table->boolean('is_variation')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
