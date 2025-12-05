<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_accounts', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('customer_id')->index();
            $t->uuid('branch_id')->index();
            $t->unsignedSmallInteger('stamps')->default(0); // 0..9
            $t->unsignedInteger('lifetime')->default(0);
            $t->timestamps();
            $t->unique(['customer_id', 'branch_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('loyalty_accounts');
    }
};
