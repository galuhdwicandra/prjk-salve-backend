<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Header
        Schema::create('wash_notes', function (Blueprint $t) {
            $t->uuid('id')->primary();
            // penting: users.id = bigint ⇒ gunakan unsignedBigInteger
            $t->unsignedBigInteger('user_id');
            $t->uuid('branch_id')->nullable(); // opsional, diisi cabang asal petugas
            $t->date('note_date');             // tanggal catatan
            $t->integer('orders_count')->default(0);
            $t->decimal('total_qty', 12, 2)->default(0);
            $t->timestamps();

            $t->unique(['user_id', 'note_date']);
            $t->index(['note_date']);
        });

        // Detail
        Schema::create('wash_note_items', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('wash_note_id');
            $t->uuid('order_id'); // refer ke orders.id (UUID)
            $t->decimal('qty', 10, 2)->default(0);
            // gunakan varchar(20) mengikuti pola status di SOP
            $t->string('process_status', 20)->nullable(); // QUEUE/WASH/DRY/FINISHING/COMPLETED/PICKED_UP
            $t->time('started_at')->nullable();
            $t->time('finished_at')->nullable();
            $t->string('note', 200)->nullable();
            $t->timestamps();

            $t->unique(['wash_note_id', 'order_id']);
            $t->index(['order_id']);
        });

        // FK (dipisah agar urutan tabel aman)
        Schema::table('wash_notes', function (Blueprint $t) {
            $t->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('wash_note_items', function (Blueprint $t) {
            $t->foreign('wash_note_id')->references('id')->on('wash_notes')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wash_note_items');
        Schema::dropIfExists('wash_notes');
    }
};
