<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Setting;
use App\Models\StockOpname;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'Stockify']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'role' => User::ROLE_ADMIN,
                'password' => bcrypt('password'),
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Budi Manajer',
                'role' => User::ROLE_MANAGER,
                'password' => bcrypt('password'),
            ]
        );

        $staff = User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Siti Staff',
                'role' => User::ROLE_STAFF,
                'password' => bcrypt('password'),
            ]
        );

        $categories = collect([
            'Elektronik' => 'elektronik',
            'Alat Tulis' => 'alat-tulis',
            'Minuman' => 'minuman',
            'Snack' => 'snack',
            'Kebersihan' => 'kebersihan',
        ])->map(fn ($slug, $name) => Category::updateOrCreate(['slug' => $slug], ['name' => $name]));

        $suppliers = collect([
            ['name' => 'PT Sumber Jaya Elektronik', 'phone' => '0812-3456-7890', 'email' => 'sales@sumberjaya.co.id', 'contact_person' => 'Andi Wijaya', 'address' => 'Jl. Raya Kalimalang No. 12, Jakarta Timur'],
            ['name' => 'CV Kertas Nusantara', 'phone' => '0813-9876-5432', 'email' => 'order@kertasnusantara.co.id', 'contact_person' => 'Rina Marlina', 'address' => 'Jl. Pahlawan No. 45, Bandung'],
            ['name' => 'PT Sehat Makmur Beverage', 'phone' => '0821-2233-4455', 'email' => 'cs@sehatmakmur.co.id', 'contact_person' => 'Dedi Kurniawan', 'address' => 'Jl. Gatot Subroto No. 8, Bekasi'],
            ['name' => 'UD Camilan Enak', 'phone' => '0857-1122-3344', 'email' => 'hallo@camilanenak.co.id', 'contact_person' => 'Sari Rahayu', 'address' => 'Jl. Merdeka No. 21, Bogor'],
            ['name' => 'PT Bersih Prima', 'phone' => '0896-5566-7788', 'email' => 'sales@bersihprima.co.id', 'contact_person' => 'Joko Santoso', 'address' => 'Jl. Industri Raya No. 3, Karawang'],
        ]);

        $supplierModels = $suppliers->map(fn ($data) => Supplier::updateOrCreate(['name' => $data['name']], $data));

        $productSpecs = [
            ['cat' => 0, 'sup' => 0, 'sku' => 'ELK-001', 'name' => 'Mouse Wireless Logitech', 'stock' => 42, 'min_stock' => 10, 'pp' => 95000, 'sp' => 135000, 'attrs' => [['Warna', 'Hitam']]],
            ['cat' => 0, 'sup' => 0, 'sku' => 'ELK-002', 'name' => 'Keyboard Mekanik RGB', 'stock' => 6, 'min_stock' => 8, 'pp' => 280000, 'sp' => 375000, 'attrs' => [['Layout', 'QWERTY Indonesia'], ['Switch', 'Red']]],
            ['cat' => 0, 'sup' => 0, 'sku' => 'ELK-003', 'name' => 'Kabel USB-C 2 Meter', 'stock' => 0, 'min_stock' => 15, 'pp' => 15000, 'sp' => 25000, 'attrs' => [['Panjang', '2 Meter']]],
            ['cat' => 1, 'sup' => 1, 'sku' => 'ALT-001', 'name' => 'Pensil 2B Faber-Castell (Isi 12)', 'stock' => 120, 'min_stock' => 30, 'pp' => 18000, 'sp' => 26000, 'attrs' => [['Isi', '12 pcs']]],
            ['cat' => 1, 'sup' => 1, 'sku' => 'ALT-002', 'name' => 'Buku Tulis 58 Halaman (Pack 10)', 'stock' => 25, 'min_stock' => 20, 'pp' => 48000, 'sp' => 65000, 'attrs' => [['Jumlah', '10 buku'], ['Halaman', '58']]],
            ['cat' => 1, 'sup' => 1, 'sku' => 'ALT-003', 'name' => 'Spidol Whiteboard Snowman', 'stock' => 15, 'min_stock' => 20, 'pp' => 8000, 'sp' => 12000, 'attrs' => [['Warna', 'Biru']]],
            ['cat' => 2, 'sup' => 2, 'sku' => 'MIM-001', 'name' => 'Air Mineral 600ml (Dus)', 'stock' => 80, 'min_stock' => 20, 'pp' => 48000, 'sp' => 55000, 'attrs' => [['Isi', '24 botol']]],
            ['cat' => 2, 'sup' => 2, 'sku' => 'MIM-002', 'name' => 'Kopi Instan Sachet (Dus)', 'stock' => 14, 'min_stock' => 15, 'pp' => 60000, 'sp' => 72000, 'attrs' => [['Isi', '30 sachet'], ['Rasa', 'Original']]],
            ['cat' => 3, 'sup' => 3, 'sku' => 'SNK-001', 'name' => 'Biskuit Coklat (Pack 20)', 'stock' => 60, 'min_stock' => 25, 'pp' => 52000, 'sp' => 68000, 'attrs' => [['Isi', '20 bungkus']]],
            ['cat' => 3, 'sup' => 3, 'sku' => 'SNK-002', 'name' => 'Keripik Kentang 68gr', 'stock' => 5, 'min_stock' => 12, 'pp' => 9500, 'sp' => 14500, 'attrs' => [['Rasa', 'Sapi Panggang']]],
            ['cat' => 4, 'sup' => 4, 'sku' => 'KBS-001', 'name' => 'Sabun Cuci Piring 800ml', 'stock' => 35, 'min_stock' => 10, 'pp' => 18000, 'sp' => 24000, 'attrs' => [['Ukuran', '800 ml']]],
            ['cat' => 4, 'sup' => 4, 'sku' => 'KBS-002', 'name' => 'Tisu Gulung (Pack 12)', 'stock' => 18, 'min_stock' => 15, 'pp' => 65000, 'sp' => 82000, 'attrs' => [['Isi', '12 gulung']]],
        ];

        $products = collect($productSpecs)->map(function ($spec) use ($categories, $supplierModels) {
            $product = Product::updateOrCreate(
                ['sku' => $spec['sku']],
                [
                    'category_id' => $categories->values()[$spec['cat']]->id,
                    'supplier_id' => $supplierModels->values()[$spec['sup']]->id,
                    'name' => $spec['name'],
                    'stock' => $spec['stock'],
                    'min_stock' => $spec['min_stock'],
                    'purchase_price' => $spec['pp'],
                    'selling_price' => $spec['sp'],
                    'description' => 'Produk contoh untuk modul '.$categories->values()[$spec['cat']]->name.'.',
                ]
            );

            $product->attributes()->delete();
            foreach ($spec['attrs'] as [$name, $value]) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'name' => $name,
                    'value' => $value,
                ]);
            }

            return $product;
        });

        $today = now();

        $transactions = [
            // Barang masuk
            ['product' => 0, 'type' => 'in', 'qty' => 50, 'sup' => 0, 'user' => 1, 'date' => 45, 'note' => 'Pembelian awal'],
            ['product' => 1, 'type' => 'in', 'qty' => 20, 'sup' => 0, 'user' => 1, 'date' => 40, 'note' => 'Pembelian awal'],
            ['product' => 2, 'type' => 'in', 'qty' => 40, 'sup' => 0, 'user' => 1, 'date' => 38, 'note' => 'Pembelian awal'],
            ['product' => 3, 'type' => 'in', 'qty' => 150, 'sup' => 1, 'user' => 1, 'date' => 35, 'note' => 'Pembelian awal'],
            ['product' => 4, 'type' => 'in', 'qty' => 40, 'sup' => 1, 'user' => 1, 'date' => 34, 'note' => 'Pembelian awal'],
            ['product' => 5, 'type' => 'in', 'qty' => 40, 'sup' => 1, 'user' => 1, 'date' => 33, 'note' => 'Pembelian awal'],
            ['product' => 6, 'type' => 'in', 'qty' => 100, 'sup' => 2, 'user' => 1, 'date' => 30, 'note' => 'Pembelian awal'],
            ['product' => 7, 'type' => 'in', 'qty' => 30, 'sup' => 2, 'user' => 1, 'date' => 28, 'note' => 'Pembelian awal'],
            ['product' => 8, 'type' => 'in', 'qty' => 80, 'sup' => 3, 'user' => 1, 'date' => 25, 'note' => 'Pembelian awal'],
            ['product' => 9, 'type' => 'in', 'qty' => 30, 'sup' => 3, 'user' => 1, 'date' => 24, 'note' => 'Pembelian awal'],
            ['product' => 10, 'type' => 'in', 'qty' => 50, 'sup' => 4, 'user' => 1, 'date' => 22, 'note' => 'Pembelian awal'],
            ['product' => 11, 'type' => 'in', 'qty' => 24, 'sup' => 4, 'user' => 1, 'date' => 20, 'note' => 'Pembelian awal'],
            // Barang masuk tambahan
            ['product' => 0, 'type' => 'in', 'qty' => 10, 'sup' => 0, 'user' => 1, 'date' => 10, 'note' => 'Restock'],
            ['product' => 7, 'type' => 'in', 'qty' => 10, 'sup' => 2, 'user' => 1, 'date' => 7, 'note' => 'Restock'],
            ['product' => 3, 'type' => 'in', 'qty' => 20, 'sup' => 1, 'user' => 1, 'date' => 5, 'note' => 'Restock'],
            // Barang keluar
            ['product' => 0, 'type' => 'out', 'qty' => 12, 'sup' => null, 'user' => 2, 'date' => 15, 'note' => 'Penjualan ke customer'],
            ['product' => 1, 'type' => 'out', 'qty' => 14, 'sup' => null, 'user' => 2, 'date' => 12, 'note' => 'Penjualan ke customer'],
            ['product' => 2, 'type' => 'out', 'qty' => 40, 'sup' => null, 'user' => 2, 'date' => 11, 'note' => 'Barang rusak dibuang'],
            ['product' => 3, 'type' => 'out', 'qty' => 45, 'sup' => null, 'user' => 2, 'date' => 9, 'note' => 'Penjualan ke customer'],
            ['product' => 4, 'type' => 'out', 'qty' => 15, 'sup' => null, 'user' => 2, 'date' => 8, 'note' => 'Penjualan ke customer'],
            ['product' => 6, 'type' => 'out', 'qty' => 20, 'sup' => null, 'user' => 2, 'date' => 6, 'note' => 'Penjualan ke customer'],
            ['product' => 8, 'type' => 'out', 'qty' => 20, 'sup' => null, 'user' => 2, 'date' => 4, 'note' => 'Penjualan ke customer'],
            ['product' => 9, 'type' => 'out', 'qty' => 25, 'sup' => null, 'user' => 2, 'date' => 3, 'note' => 'Penjualan ke customer'],
            ['product' => 10, 'type' => 'out', 'qty' => 15, 'sup' => null, 'user' => 2, 'date' => 2, 'note' => 'Penjualan ke customer'],
            ['product' => 11, 'type' => 'out', 'qty' => 6, 'sup' => null, 'user' => 2, 'date' => 1, 'note' => 'Penjualan ke customer'],
        ];

        $productModels = $products->values();

        foreach ($transactions as $t) {
            $product = $productModels[$t['product']];
            StockTransaction::create([
                'product_id' => $product->id,
                'type' => $t['type'],
                'quantity' => $t['qty'],
                'price' => $t['type'] === 'in' ? $product->purchase_price : $product->selling_price,
                'supplier_id' => $t['sup'] !== null ? $supplierModels->values()[$t['sup']]->id : null,
                'user_id' => $t['user'] === 1 ? $manager->id : $staff->id,
                'note' => $t['note'],
                'transaction_date' => $today->copy()->subDays($t['date']),
            ]);
        }

        // Stock opname
        $opnames = [
            ['product' => 3, 'system' => 120, 'actual' => 118, 'user' => 2, 'date' => 6, 'note' => 'Selisih karena pensil patah'],
            ['product' => 6, 'system' => 80, 'actual' => 80, 'user' => 2, 'date' => 4, 'note' => 'Cocok'],
            ['product' => 0, 'system' => 42, 'actual' => 42, 'user' => 2, 'date' => 2, 'note' => 'Cocok'],
        ];

        foreach ($opnames as $o) {
            StockOpname::create([
                'product_id' => $productModels[$o['product']]->id,
                'system_qty' => $o['system'],
                'actual_qty' => $o['actual'],
                'difference' => $o['actual'] - $o['system'],
                'note' => $o['note'],
                'user_id' => $o['user'] === 2 ? $staff->id : $manager->id,
                'opname_date' => $today->copy()->subDays($o['date']),
            ]);
        }

        // Activity log ringkasan
        ActivityLog::create(['user_id' => $admin->id, 'action' => 'Login', 'description' => 'Administrator masuk ke sistem', 'created_at' => $today->copy()->subDay()]);
        ActivityLog::create(['user_id' => $manager->id, 'action' => 'Barang Masuk', 'description' => 'Mencatat transaksi barang masuk', 'created_at' => $today->copy()->subHours(5)]);
        ActivityLog::create(['user_id' => $staff->id, 'action' => 'Stock Opname', 'description' => 'Melakukan perhitungan stock opname', 'created_at' => $today->copy()->subHours(2)]);

        $this->command?->info('Seeder selesai.');
        $this->command?->info('Admin  : admin@example.com / password');
        $this->command?->info('Manajer: manager@example.com / password');
        $this->command?->info('Staff  : staff@example.com / password');
    }
}
