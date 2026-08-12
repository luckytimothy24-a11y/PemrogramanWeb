<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_product_creation_with_attributes(): void
    {
        $category = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $supplier = Supplier::create(['name' => 'PT Sumber Jaya']);

        $response = $this->actingAs($this->admin())->post('/products', [
            'name' => 'Kaos Polos',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'purchase_price' => 30000,
            'selling_price' => 45000,
            'stock' => 10,
            'min_stock' => 2,
            'attributes' => [
                0 => ['name' => 'Ukuran', 'value' => 'L'],
                1 => ['name' => 'Warna', 'value' => 'Hitam'],
                2 => ['name' => '', 'value' => ''],
            ],
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Kaos Polos',
            'supplier_id' => $supplier->id,
        ]);

        $product = \App\Models\Product::where('name', 'Kaos Polos')->first();
        $this->assertCount(2, $product->attributes);
        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $product->id,
            'name' => 'Ukuran',
            'value' => 'L',
        ]);
    }

    public function test_product_can_be_created_without_supplier(): void
    {
        $category = Category::create(['name' => 'Makanan', 'slug' => 'makanan']);

        $response = $this->actingAs($this->admin())->post('/products', [
            'name' => 'Roti Tawar',
            'category_id' => $category->id,
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 20,
            'min_stock' => 5,
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Roti Tawar',
            'supplier_id' => null,
        ]);
    }
}
