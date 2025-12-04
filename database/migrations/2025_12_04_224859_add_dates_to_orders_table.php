<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->timestamp('received_at')->nullable()->after('customer_id');
            $t->timestamp('ready_at')->nullable()->after('received_at');
            $t->index('received_at');
            $t->index('ready_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropIndex(['received_at']);
            $t->dropIndex(['ready_at']);
            $t->dropColumn(['received_at', 'ready_at']);
        });
    }
};
