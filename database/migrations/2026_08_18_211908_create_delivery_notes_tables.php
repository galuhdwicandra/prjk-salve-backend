<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number', 40)->unique();
            $table->enum('kind', ['kirim', 'ambil']);
            $table->date('note_date');
            $table->uuid('branch_id');
            $table->enum('to_type', ['workshop', 'vendor']);
            $table->uuid('to_branch_id')->nullable();
            $table->char('to_contact_id', 36)->nullable();
            $table->char('from_contact_id', 36)->nullable();
            $table->string('status', 20)->default('PENDING');
            $table->json('proofs')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('picked_by')->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['kind', 'status']);
            $table->index(['branch_id', 'note_date']);

            $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            $table->foreign('to_branch_id')->references('id')->on('branches')->nullOnDelete();
            $table->foreign('to_contact_id')->references('id')->on('contacts')->nullOnDelete();
            $table->foreign('from_contact_id')->references('id')->on('contacts')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('picked_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completed_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('delivery_note_orders', function (Blueprint $table) {
            $table->uuid('delivery_note_id');
            $table->uuid('order_id');

            $table->primary(['delivery_note_id', 'order_id']);
            $table->index('order_id');

            $table->foreign('delivery_note_id')->references('id')->on('delivery_notes')->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_note_orders');
        Schema::dropIfExists('delivery_notes');
    }
};
