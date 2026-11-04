<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Gói sticker BiếtTuốt', 'description' => 'Quà cảm ơn cộng đồng.', 'cost_points' => 100],
            ['name' => 'Huy hiệu cộng tác viên', 'description' => 'Huy hiệu ghi nhận đóng góp.', 'cost_points' => 250],
            ['name' => 'Voucher học tập', 'description' => 'Voucher mẫu; quản trị viên cần cập nhật điều khoản trước khi cung cấp.', 'cost_points' => 500],
        ] as $reward) {
            Reward::firstOrCreate(['name' => $reward['name']], $reward + ['active' => true]);
        }
    }
}
