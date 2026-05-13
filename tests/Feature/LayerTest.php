<?php

namespace Tests\Feature;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Supplier $supplier;
    private Layup $layup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
        $this->layup = Layup::factory()->create(['supplier_id' => $this->supplier->id]);
    }

    public function test_can_create_layer(): void
    {
        $this->actingAs($this->user)
            ->post(route('suppliers.layups.layers.store', [$this->supplier, $this->layup]), [
                'layer_order' => 1,
                'thickness'   => 40.0,
                'width'       => 200.0,
                'angle'       => 0,
            ])
            ->assertRedirect(route('suppliers.layups.show', [$this->supplier, $this->layup]));

        $this->assertDatabaseHas('clt_layers', ['layup_id' => $this->layup->id, 'layer_order' => 1, 'thickness' => 40]);
    }

    public function test_layer_order_must_be_unique_per_layup(): void
    {
        Layer::factory()->create(['layup_id' => $this->layup->id, 'layer_order' => 1]);

        $this->actingAs($this->user)
            ->post(route('suppliers.layups.layers.store', [$this->supplier, $this->layup]), [
                'layer_order' => 1,
                'thickness'   => 40,
                'width'       => 200,
                'angle'       => 0,
            ])
            ->assertSessionHasErrors('layer_order');
    }

    public function test_can_update_layer(): void
    {
        $layer = Layer::factory()->create(['layup_id' => $this->layup->id, 'layer_order' => 1]);

        $this->actingAs($this->user)
            ->patch(route('suppliers.layups.layers.update', [$this->supplier, $this->layup, $layer]), [
                'layer_order' => 1,
                'thickness'   => 55,
                'width'       => 300,
                'angle'       => 90,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('clt_layers', ['id' => $layer->id, 'thickness' => 55]);
    }

    public function test_can_delete_layer(): void
    {
        $layer = Layer::factory()->create(['layup_id' => $this->layup->id, 'layer_order' => 1]);

        $this->actingAs($this->user)
            ->delete(route('suppliers.layups.layers.destroy', [$this->supplier, $this->layup, $layer]))
            ->assertRedirect(route('suppliers.layups.show', [$this->supplier, $this->layup]));

        $this->assertDatabaseMissing('clt_layers', ['id' => $layer->id]);
    }

    public function test_layer_requires_all_fields(): void
    {
        $this->actingAs($this->user)
            ->post(route('suppliers.layups.layers.store', [$this->supplier, $this->layup]), [])
            ->assertSessionHasErrors(['layer_order', 'thickness', 'width', 'angle']);
    }
}
