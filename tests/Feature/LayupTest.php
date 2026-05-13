<?php

namespace Tests\Feature;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayupTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
    }

    public function test_can_create_layup(): void
    {
        $this->actingAs($this->user)
            ->post(route('suppliers.layups.store', $this->supplier), ['name' => 'CLT-5L'])
            ->assertRedirect(route('suppliers.show', $this->supplier));

        $this->assertDatabaseHas('clt_layups', ['supplier_id' => $this->supplier->id, 'name' => 'CLT-5L']);
    }

    public function test_layup_name_must_be_unique_per_supplier(): void
    {
        Layup::factory()->create(['supplier_id' => $this->supplier->id, 'name' => 'CLT-5L']);

        $this->actingAs($this->user)
            ->post(route('suppliers.layups.store', $this->supplier), ['name' => 'CLT-5L'])
            ->assertSessionHasErrors('name');
    }

    public function test_same_layup_name_allowed_on_different_supplier(): void
    {
        $other = Supplier::factory()->create();
        Layup::factory()->create(['supplier_id' => $other->id, 'name' => 'CLT-5L']);

        $this->actingAs($this->user)
            ->post(route('suppliers.layups.store', $this->supplier), ['name' => 'CLT-5L'])
            ->assertRedirect();

        $this->assertDatabaseHas('clt_layups', ['supplier_id' => $this->supplier->id, 'name' => 'CLT-5L']);
    }

    public function test_can_update_layup(): void
    {
        $layup = Layup::factory()->create(['supplier_id' => $this->supplier->id]);

        $this->actingAs($this->user)
            ->patch(route('suppliers.layups.update', [$this->supplier, $layup]), ['name' => 'Updated'])
            ->assertRedirect();

        $this->assertDatabaseHas('clt_layups', ['id' => $layup->id, 'name' => 'Updated']);
    }

    public function test_can_delete_layup(): void
    {
        $layup = Layup::factory()->create(['supplier_id' => $this->supplier->id]);

        $this->actingAs($this->user)
            ->delete(route('suppliers.layups.destroy', [$this->supplier, $layup]))
            ->assertRedirect(route('suppliers.show', $this->supplier));

        $this->assertDatabaseMissing('clt_layups', ['id' => $layup->id]);
    }

    public function test_deleting_layup_also_deletes_layers(): void
    {
        $layup = Layup::factory()->create(['supplier_id' => $this->supplier->id]);
        $layer = Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1]);

        $this->actingAs($this->user)
            ->delete(route('suppliers.layups.destroy', [$this->supplier, $layup]));

        $this->assertDatabaseMissing('clt_layers', ['id' => $layer->id]);
    }
}
