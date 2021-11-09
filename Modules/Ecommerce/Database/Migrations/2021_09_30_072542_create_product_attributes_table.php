<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_variation_id')->constrained('product_variations')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete();
            $table->foreignId('attribute_set_id')->constrained('attribute_sets')->cascadeOnDelete();
            $table->boolean('default')->default(false);
            $table->index('product_variation_id');
            $table->index('attribute_set_id');
            $table->index(['attribute_set_id', 'product_variation_id']);
            $table->index(['attribute_id', 'attribute_set_id', 'product_variation_id'],'attribute_and_a_set_id_and_p_v_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
