<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('primary_color')->default('#000000');
            $table->string('secondary_color')->default('#ffffff');
            $table->string('font')->default('Arial');
            $table->string('logo_position')->default('center');
            $table->boolean('show_organization_logo')->default(true);
            $table->boolean('show_project_logo')->default(false);
            $table->text('footer_text')->nullable();
            $table->text('additional_information')->nullable();
            $table->boolean('has_watermark')->default(false);
            $table->string('watermark_text')->nullable();
            $table->boolean('has_signature')->default(false);
            $table->string('signature_image')->nullable();
            $table->string('signature_position')->default('right');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_templates');
    }
};