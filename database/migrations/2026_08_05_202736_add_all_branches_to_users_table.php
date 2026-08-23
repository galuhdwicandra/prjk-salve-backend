<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('all_branches')->default(false)->after('manager');
        });

        $superadminIds = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->where('roles.name', 'Superadmin')
            ->pluck('model_has_roles.model_id');

        if ($superadminIds->isNotEmpty()) {
            DB::table('users')
                ->whereIn('id', $superadminIds)
                ->update(['all_branches' => true, 'manager' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('all_branches');
        });
    }
};
