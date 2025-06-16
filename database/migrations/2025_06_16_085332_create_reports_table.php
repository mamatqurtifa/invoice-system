<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'project_based']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_orders');
            $table->decimal('total_revenue', 15, 2);
            $table->decimal('total_tax', 15, 2);
            $table->decimal('total_discount', 15, 2);
            $table->enum('status', ['generated', 'archived'])->default('generated');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};