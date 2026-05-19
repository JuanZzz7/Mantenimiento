<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('restrict');
            $table->enum('type', ['correctiva', 'preventiva'])->default('correctiva');
            $table->enum('priority', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->enum('status', ['pendiente', 'en_proceso', 'completada', 'cancelada'])->default('pendiente');
            $table->text('description');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('maintenance_plan_id')->nullable()->constrained('maintenance_plans')->onDelete('set null');
            $table->dateTime('scheduled_date')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
