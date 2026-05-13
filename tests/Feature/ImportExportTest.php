<?php

namespace Tests\Feature;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Tests\TestCase;

class ImportExportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user     = User::factory()->create();
        $this->supplier = Supplier::factory()->create(['name' => 'Test Supplier']);
    }

    private function makeXlsx(array $sheets): UploadedFile
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($sheets as $title => $rows) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(mb_substr($title, 0, 31));
            $sheet->fromArray($rows, null, 'A1');
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'test_xlsx_') . '.xlsx';
        (new XlsxWriter($spreadsheet))->save($tmpPath);

        return new UploadedFile(
            $tmpPath,
            'import.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
    }

    public function test_export_returns_xlsx_download(): void
    {
        $layup = Layup::factory()->create(['supplier_id' => $this->supplier->id, 'name' => 'CLT-3L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 1200, 'angle' => 0]);

        $response = $this->actingAs($this->user)
            ->get(route('suppliers.export', $this->supplier));

        $response->assertOk()
            ->assertHeader(
                'Content-Type',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );
    }

    public function test_import_creates_new_layups(): void
    {
        $file = $this->makeXlsx([
            'New-CLT' => [
                ['layer_order', 'thickness', 'width', 'angle'],
                [1, 40, 200, 0],
                [2, 40, 200, 90],
            ],
        ]);

        $this->actingAs($this->user)
            ->post(route('suppliers.import', $this->supplier), [
                'file'     => $file,
                'strategy' => 'overwrite',
            ])
            ->assertRedirect(route('suppliers.show', $this->supplier));

        $this->assertDatabaseHas('clt_layups', ['supplier_id' => $this->supplier->id, 'name' => 'New-CLT']);
        $this->assertDatabaseCount('clt_layers', 2);
    }

    public function test_import_requires_xlsx_file(): void
    {
        $file = UploadedFile::fake()->create('import.csv', 100, 'text/csv');

        $this->actingAs($this->user)
            ->post(route('suppliers.import', $this->supplier), [
                'file'     => $file,
                'strategy' => 'overwrite',
            ])
            ->assertSessionHasErrors('file');
    }

    public function test_import_requires_strategy(): void
    {
        $file = $this->makeXlsx([
            'Some Layup' => [['layer_order', 'thickness', 'width', 'angle']],
        ]);

        $this->actingAs($this->user)
            ->post(route('suppliers.import', $this->supplier), [
                'file' => $file,
            ])
            ->assertSessionHasErrors('strategy');
    }

    public function test_import_overwrite_resolves_conflict(): void
    {
        $layup = Layup::factory()->create(['supplier_id' => $this->supplier->id, 'name' => 'CLT-5L']);
        Layer::factory()->create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 40, 'width' => 200, 'angle' => 0]);

        $file = $this->makeXlsx([
            'CLT-5L' => [
                ['layer_order', 'thickness', 'width', 'angle'],
                [1, 60, 250, 90],
            ],
        ]);

        $this->actingAs($this->user)
            ->post(route('suppliers.import', $this->supplier), [
                'file'     => $file,
                'strategy' => 'overwrite',
            ])
            ->assertRedirect(route('suppliers.show', $this->supplier));

        $this->assertDatabaseHas('clt_layers', ['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 60]);
    }
}

