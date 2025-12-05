<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->string('loyalty_reward', 16)->default('NONE'); // NONE|DISC25|FREE100
            $t->decimal('loyalty_discount', 12, 2)->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropColumn(['loyalty_reward', 'loyalty_discount']);
        });
    }
};
