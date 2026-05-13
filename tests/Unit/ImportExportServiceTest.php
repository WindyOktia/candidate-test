<?php

namespace Tests\Unit;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Repositories\LayerRepository;
use App\Repositories\LayupRepository;
use App\Repositories\SupplierRepository;
use App\Services\ImportExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportExportServiceTest extends TestCase
{
    use RefreshDatabase;

    private ImportExportService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ImportExportService(
            new LayupRepository(),
            new LayerRepository(),
        );
    }

    public function test_export_returns_correct_structure(): void
    {
        $supplier = Supplier::factory()->create(['name' => 'ACME Timber']);
        $layup = Layup::factory()->create(['supplier_id' => $supplier->id, 'name' => 'CLT-5L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $rows = $this->service->exportSupplier($supplier);

        $this->assertCount(1, $rows);
        $this->assertEquals('CLT-5L', $rows[0]['layup_name']);
        $this->assertEquals(1, $rows[0]['layer_order']);
        $this->assertEquals(40, $rows[0]['thickness']);
    }

    public function test_detect_conflicts_finds_differing_layer(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->create(['supplier_id' => $supplier->id, 'name' => 'CLT-3L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $importData = [
            'layups' => [[
                'name'   => 'CLT-3L',
                'layers' => [['layer_order' => 1, 'thickness' => 50, 'width' => 200, 'angle' => 0]],
            ]],
        ];

        $conflicts = $this->service->detectConflicts($supplier, $importData);

        $this->assertCount(1, $conflicts);
        $this->assertArrayHasKey('thickness', $conflicts[0]['differences']);
    }

    public function test_detect_conflicts_returns_empty_when_no_conflict(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->create(['supplier_id' => $supplier->id, 'name' => 'CLT-3L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $importData = [
            'layups' => [[
                'name'   => 'CLT-3L',
                'layers' => [['layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]],
            ]],
        ];

        $conflicts = $this->service->detectConflicts($supplier, $importData);

        $this->assertCount(0, $conflicts);
    }

    public function test_import_overwrite_updates_existing_layer(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->create(['supplier_id' => $supplier->id, 'name' => 'CLT-3L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $importData = [
            'layups' => [[
                'name'   => 'CLT-3L',
                'layers' => [['layer_order' => 1, 'thickness' => 60, 'width' => 250, 'angle' => 90]],
            ]],
        ];

        $this->service->importSupplier($supplier, $importData, 'overwrite');

        $this->assertDatabaseHas('clt_layers', [
            'layup_id'    => $layup->id,
            'layer_order' => 1,
            'thickness'   => 60,
        ]);
    }

    public function test_import_skip_keeps_existing_layer(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->create(['supplier_id' => $supplier->id, 'name' => 'CLT-3L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $importData = [
            'layups' => [[
                'name'   => 'CLT-3L',
                'layers' => [['layer_order' => 1, 'thickness' => 60, 'width' => 250, 'angle' => 90]],
            ]],
        ];

        $this->service->importSupplier($supplier, $importData, 'skip');

        $this->assertDatabaseHas('clt_layers', [
            'layup_id'    => $layup->id,
            'layer_order' => 1,
            'thickness'   => 40,
        ]);
    }

    public function test_import_creates_new_layup_when_not_existing(): void
    {
        $supplier = Supplier::factory()->create();

        $importData = [
            'layups' => [[
                'name'   => 'New-Layup',
                'layers' => [['layer_order' => 1, 'thickness' => 30, 'width' => 150, 'angle' => 45]],
            ]],
        ];

        $this->service->importSupplier($supplier, $importData, 'skip');

        $this->assertDatabaseHas('clt_layups', ['supplier_id' => $supplier->id, 'name' => 'New-Layup']);
        $this->assertDatabaseHas('clt_layers', ['thickness' => 30, 'angle' => 45]);
    }

    public function test_import_duplicate_creates_suffixed_layup(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->create(['supplier_id' => $supplier->id, 'name' => 'CLT-3L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $importData = [
            'layups' => [[
                'name'   => 'CLT-3L',
                'layers' => [['layer_order' => 1, 'thickness' => 60, 'width' => 250, 'angle' => 90]],
            ]],
        ];

        $this->service->importSupplier($supplier, $importData, 'duplicate');

        $this->assertDatabaseHas('clt_layups', ['supplier_id' => $supplier->id, 'name' => 'CLT-3L (imported)']);
    }
}
