<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_logs', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('order_id')->nullable()->unique();
            $t->uuid('customer_id')->index();
            $t->uuid('branch_id')->index();
            $t->string('action', 20); // EARN | REWARD25 | REWARD100 | RESET | ADJUST
            $t->unsignedSmallInteger('before')->default(0);
            $t->unsignedSmallInteger('after')->default(0);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('loyalty_logs');
    }
};
