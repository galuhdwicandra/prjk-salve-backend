<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ALL_MODULES = [
        'dashboard',
        'kasir-pos', 'kasir-receipt', 'kasir-customer', 'kasir-promo',
        'ops-sorting', 'ops-proses', 'ops-kirim', 'ops-tracker',
        'fin-kas', 'fin-transaksi', 'fin-kontak',
        'laporan',
        'set-user', 'set-master', 'set-outlet', 'set-coa', 'set-jurnal',
        'set-labels', 'set-paymethod', 'set-num', 'set-wa',
    ];

    public function up(): void
    {
        $map = [
            'Superadmin'   => [
                'modules' => self::ALL_MODULES,
                'manager' => true, 'show_balance' => true, 'custom_price' => true,
            ],
            'Admin Cabang' => [
                'modules' => [
                    'dashboard',
                    'kasir-pos', 'kasir-receipt', 'kasir-customer', 'kasir-promo',
                    'ops-proses', 'ops-kirim',
                    'fin-transaksi',
                    'laporan',
                    'set-user', 'set-master', 'set-wa',
                ],
                'manager' => true, 'show_balance' => true, 'custom_price' => true,
            ],
            'Kasir'        => [
                'modules' => [
                    'dashboard',
                    'kasir-pos', 'kasir-receipt', 'kasir-customer', 'kasir-promo',
                    'laporan',
                ],
                'manager' => false, 'show_balance' => false, 'custom_price' => false,
            ],
            'Petugas Cuci' => [
                'modules' => ['dashboard', 'ops-proses'],
                'manager' => false, 'show_balance' => false, 'custom_price' => false,
            ],
            'Kurir'        => [
                'modules' => ['dashboard', 'ops-kirim'],
                'manager' => false, 'show_balance' => false, 'custom_price' => false,
            ],
            'Akuntansi'    => [
                'modules' => ['dashboard', 'laporan', 'set-coa', 'set-jurnal'],
                'manager' => false, 'show_balance' => true, 'custom_price' => false,
            ],
        ];

        $priority = array_flip(array_keys($map));

        $rows = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->whereIn('roles.name', array_keys($map))
            ->get(['model_has_roles.model_id as user_id', 'roles.name as role']);

        $merged = [];

        foreach ($rows as $row) {
            $rule    = $map[$row->role];
            $current = $merged[$row->user_id] ?? [
                'role_label'   => $row->role,
                'modules'      => [],
                'manager'      => false,
                'show_balance' => false,
                'custom_price' => false,
            ];

            $current['modules']      = array_values(array_unique(array_merge($current['modules'], $rule['modules'])));
            $current['manager']      = $current['manager'] || $rule['manager'];
            $current['show_balance'] = $current['show_balance'] || $rule['show_balance'];
            $current['custom_price'] = $current['custom_price'] || $rule['custom_price'];

            if ($priority[$row->role] < $priority[$current['role_label']]) {
                $current['role_label'] = $row->role;
            }

            $merged[$row->user_id] = $current;
        }

        foreach ($merged as $userId => $access) {
            DB::table('users')->where('id', $userId)->update([
                'role_label'   => $access['role_label'],
                'modules'      => json_encode($access['modules']),
                'manager'      => $access['manager'],
                'show_balance' => $access['show_balance'],
                'custom_price' => $access['custom_price'],
            ]);
        }

        DB::table('users')->whereNull('modules')->update([
            'modules' => json_encode(['dashboard']),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->update([
            'role_label'   => null,
            'modules'      => null,
            'manager'      => false,
            'show_balance' => false,
            'custom_price' => false,
        ]);
    }
};
