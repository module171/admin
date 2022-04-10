<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $this->create_bill();
        $this->create_image();
        $this->create_cate();
        $this->create_item();
        $this->create_order();
        $this->create_order_detail();
        $this->bill();

    }
    public function create_item()
    {

        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cat_id')->nullable();
            $table->string('item_name')->nullable();
            $table->longText('item_description')->nullable();
            $table->string('item_price')->nullable();
            $table->foreign('cat_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('item_status')->nullable();
            $table->timestamps();
        });
    }
    public function create_bill()
    {

        Schema::create('bill', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function create_order_detail()
    {


        Schema::create('order_detail', function (Blueprint $table) {

            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->integer('qly')->nullable();
            $table->timestamps();
        });
    }


public function bill(){

    Schema::create('bill', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->unsignedBigInteger('order_id')->nullable();

        $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');

        $table->timestamps();
    });
}
    public function create_order()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('order_type')->nullable();
            $table->string('address')->nullable();
            $table->string('promotecode')->nullable();
            $table->string('tax')->nullable();
            $table->text('order_notes')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    public function create_ship(){

        Schema::create('shipper', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('password')->nullable();
            $table->string('money_earned')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });

    }
    public function create_image()
    {


        Schema::create('item_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->string('image');
            $table->timestamps();
        });
    }
    public function create_cate()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name');
            $table->string('image');
            $table->integer('is_available');
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
        Schema::dropIfExists('item_images');
        Schema::dropIfExists('order_detail');
        // Schema::dropIfExists('bill');
        Schema::dropIfExists('item');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('order');
        Schema::dropIfExists('users');
        Schema::dropIfExists('bill');
    }
}
