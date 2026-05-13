<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_supplier_index_requires_auth(): void
    {
        $this->get(route('suppliers.index'))->assertRedirect(route('login'));
    }

    public function test_supplier_index_lists_suppliers(): void
    {
        Supplier::factory(3)->create();

        $this->actingAs($this->user)
            ->get(route('suppliers.index'))
            ->assertOk()
            ->assertViewIs('suppliers.index')
            ->assertViewHas('suppliers');
    }

    public function test_can_create_supplier(): void
    {
        $this->actingAs($this->user)
            ->post(route('suppliers.store'), [
                'name'  => 'Test Supplier',
                'email' => 'test@supplier.com',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('suppliers', ['name' => 'Test Supplier', 'email' => 'test@supplier.com']);
    }

    public function test_create_supplier_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post(route('suppliers.store'), [])
            ->assertSessionHasErrors('name');
    }

    public function test_can_update_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $this->actingAs($this->user)
            ->patch(route('suppliers.update', $supplier), ['name' => 'Updated Name'])
            ->assertRedirect();

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('suppliers.destroy', $supplier))
            ->assertRedirect(route('suppliers.index'));

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_can_view_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $this->actingAs($this->user)
            ->get(route('suppliers.show', $supplier))
            ->assertOk()
            ->assertViewIs('suppliers.show');
    }

    public function test_supplier_code_must_be_unique(): void
    {
        Supplier::factory()->create(['code' => 'SUP-001']);

        $this->actingAs($this->user)
            ->post(route('suppliers.store'), ['name' => 'Another', 'code' => 'SUP-001'])
            ->assertSessionHasErrors('code');
    }
}
