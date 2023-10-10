<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('title')->nullable();
            $table->string('currency_unit')->nullable();
            $table->boolean('has_tax')->default(true);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->float('rows_total_price_with_tax')->nullable();
            $table->float('rows_total_price_without_tax')->nullable();
            $table->float('rows_total_tax_price')->nullable();
            
            $table->float('details_total_price_with_tax')->nullable();
            $table->float('details_total_price_without_tax')->nullable();
            $table->float('details_total_tax_price')->nullable();
            
            $table->float('total_price_with_tax')->nullable();
            $table->float('total_price_without_tax')->nullable();
            $table->float('total_tax_price')->nullable();
            
            $table->unsignedInteger('advertisement_id')->nullable();
            $table->json('data');
            $table->text('description')->nullable();

            // project columns
            $table->string('project_title')->nullable();
            $table->string('project_budget')->nullable();
            $table->string('project_size')->nullable();
            $table->string('project_type')->nullable();
            $table->dateTime('project_start_date')->nullable();
            $table->dateTime('project_end_date')->nullable();

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
        Schema::dropIfExists('pre_invoices');
    }
};
