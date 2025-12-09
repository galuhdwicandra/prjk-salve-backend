<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wash_note_items', function (Blueprint $t) {
            // Kunci global: satu order hanya boleh muncul sekali di seluruh catatan cuci
            $t->unique('order_id', 'wash_note_items_order_id_unique_global');
        });
    }

    public function down(): void
    {
        Schema::table('wash_note_items', function (Blueprint $t) {
            $t->dropUnique('wash_note_items_order_id_unique_global');
        });
    }
};
