<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ImportExportServiceInterface;
use App\Http\Requests\ImportSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportExportController extends Controller
{
    public function __construct(
        private readonly ImportExportServiceInterface $importExportService,
    ) {}

    public function exportAll(): Response
    {
        $suppliers = Supplier::withCount('layups')->orderBy('name')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Suppliers');

        $headers = ['ID', 'Name', 'Code', 'Contact Person', 'Email', 'Phone', 'Address', 'Material Certifications', 'Last Audit Date', 'Total Layups', 'Created At'];
        $sheet->fromArray($headers, null, 'A1', true);

        foreach ($suppliers as $i => $supplier) {
            $sheet->fromArray([
                $supplier->id,
                $supplier->name,
                $supplier->code,
                $supplier->contact_person,
                $supplier->email,
                $supplier->phone,
                $supplier->address,
                $supplier->material_certifications,
                $supplier->last_audit_date?->format('Y-m-d'),
                $supplier->layups_count,
                $supplier->created_at->format('Y-m-d'),
            ], null, 'A' . ($i + 2), true);
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'clt_suppliers_') . '.xlsx';
        (new XlsxWriter($spreadsheet))->save($tmpPath);

        $filename = 'suppliers-' . now()->format('Y-m-d') . '.xlsx';
        $contents = file_get_contents($tmpPath);
        unlink($tmpPath);

        return response($contents, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function export(Supplier $supplier): Response
    {
        $rows = $this->importExportService->exportSupplier($supplier);

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['layup_name']][] = $row;
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($grouped as $layupName => $layupRows) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(mb_substr($layupName, 0, 31));
            $sheet->fromArray(['layer_order', 'thickness', 'width', 'angle'], null, 'A1', true);
            $rowIndex = 2;
            foreach ($layupRows as $row) {
                $sheet->fromArray(
                    [$row['layer_order'], $row['thickness'], $row['width'], $row['angle']],
                    null,
                    'A' . $rowIndex,
                    true
                );
                $rowIndex++;
            }
        }

        if ($spreadsheet->getSheetCount() === 0) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('No Data');
            $sheet->fromArray(['layer_order', 'thickness', 'width', 'angle'], null, 'A1', true);
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'clt_export_') . '.xlsx';
        (new XlsxWriter($spreadsheet))->save($tmpPath);

        $filename = 'supplier-' . str($supplier->name)->slug() . '.xlsx';
        $contents = file_get_contents($tmpPath);
        unlink($tmpPath);

        return response($contents, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function import(ImportSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $path = $request->file('file')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Could not read uploaded file: ' . $e->getMessage()]);
        }

        $required = ['layer_order', 'thickness', 'width', 'angle'];
        $layupMap = [];

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $layupName = trim($sheet->getTitle());
            if ($layupName === '') {
                continue;
            }

            $rows = $sheet->toArray(null, true, true, false);
            if (empty($rows)) {
                continue;
            }

            $header = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows));
            $missing = array_diff($required, $header);
            if (! empty($missing)) {
                return back()->withErrors([
                    'file' => "Sheet \"{$layupName}\" is missing columns: " . implode(', ', $missing),
                ]);
            }

            foreach ($rows as $row) {
                $data = array_combine($header, $row);
                $layupMap[$layupName][] = [
                    'layer_order' => (int)   $data['layer_order'],
                    'thickness'   => (float) $data['thickness'],
                    'width'       => (float) $data['width'],
                    'angle'       => (float) $data['angle'],
                ];
            }
        }

        if (empty($layupMap)) {
            return back()->withErrors(['file' => 'File contains no layer data.']);
        }

        $importData = ['layups' => array_map(
            fn ($name, $layers) => ['name' => $name, 'layers' => $layers],
            array_keys($layupMap), array_values($layupMap)
        )];

        $strategy = $request->input('strategy');
        $dryRun   = $request->boolean('dry_run');

        if ($strategy === 'reject' || $strategy === 'resolve') {
            $conflicts = $this->importExportService->detectConflicts($supplier, $importData);
            if (! empty($conflicts)) {
                return back()
                    ->with('conflicts', $conflicts)
                    ->with('import_data', json_encode($importData))
                    ->with('dry_run', $dryRun);
            }
        }

        try {
            $summary = $this->importExportService->importSupplier($supplier, $importData, $strategy, $dryRun);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }

        if ($dryRun) {
            return redirect()->route('suppliers.show', $supplier)
                ->with('dry_run_preview', [
                    'supplier_id' => $supplier->id,
                    'summary'     => $summary,
                    'import_data' => $importData,
                    'strategy'    => $strategy,
                    'via_resolve' => false,
                ]);
        }

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Import completed successfully.');
    }

    public function detect(Request $request, Supplier $supplier): JsonResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls', 'max:8192']]);

        $path = $request->file('file')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not read file.'], 422);
        }

        $layupMap = [];
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $layupName = trim($sheet->getTitle());
            if ($layupName === '') {
                continue;
            }
            $rows = $sheet->toArray(null, true, true, false);
            if (empty($rows)) {
                continue;
            }
            $header = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows));
            foreach ($rows as $row) {
                if (count($row) < count($header)) {
                    continue;
                }
                $data = array_combine($header, $row);
                $layupMap[$layupName][] = [
                    'layer_order' => (int)   ($data['layer_order'] ?? 0),
                    'thickness'   => (float) ($data['thickness']   ?? 0),
                    'width'       => (float) ($data['width']       ?? 0),
                    'angle'       => (float) ($data['angle']       ?? 0),
                ];
            }
        }

        $importData = ['layups' => array_map(
            fn ($name, $layers) => ['name' => $name, 'layers' => $layers],
            array_keys($layupMap), array_values($layupMap)
        )];

        $conflicts = $this->importExportService->detectConflicts($supplier, $importData);

        return response()->json([
            'layup_count'    => count($importData['layups']),
            'conflict_count' => count($conflicts),
        ]);
    }

    public function resolveConflicts(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->validate([
            'import_data'   => ['required', 'string'],
            'resolutions'   => ['required', 'array'],
            'resolutions.*' => ['in:existing,incoming'],
            'dry_run'       => ['sometimes', 'boolean'],
        ]);

        $importData  = json_decode($request->input('import_data'), true);

        if (! is_array($importData)) {
            return back()->withErrors(['import_data' => 'Invalid import data.']);
        }

        $dryRun      = $request->boolean('dry_run');
        $resolutions = $request->input('resolutions', []);

        $summary = $this->importExportService->importSupplierWithResolutions(
            $supplier,
            $importData,
            $resolutions,
            $dryRun
        );

        if ($dryRun) {
            return redirect()->route('suppliers.show', $supplier)
                ->with('dry_run_preview', [
                    'supplier_id' => $supplier->id,
                    'summary'     => $summary,
                    'import_data' => $importData,
                    'resolutions' => $resolutions,
                    'via_resolve' => true,
                ]);
        }

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Import completed with manual conflict resolution.');
    }

    public function commitDryRun(Request $request, Supplier $supplier): RedirectResponse
    {
        $preview = $request->session()->get('dry_run_preview');

        if (! $preview) {
            return redirect()->route('suppliers.show', $supplier)
                ->withErrors(['file' => 'No dry run data found. Please import again.']);
        }

        $request->session()->forget('dry_run_preview');

        $importData = $preview['import_data'];

        try {
            if ($preview['via_resolve'] ?? false) {
                $this->importExportService->importSupplierWithResolutions(
                    $supplier,
                    $importData,
                    $preview['resolutions'] ?? []
                );
            } else {
                $this->importExportService->importSupplier(
                    $supplier,
                    $importData,
                    $preview['strategy']
                );
            }
        } catch (\RuntimeException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Import committed — ' . count($importData['layups']) . ' layup(s) saved successfully.');
    }

    public function discardDryRun(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->session()->forget('dry_run_preview');

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Dry run discarded. No changes were made.');
    }

    public function template(): Response
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $examples = [
            'Example Layup A' => [[1, 40, 1200, 0], [2, 20, 1200, 90], [3, 40, 1200, 0]],
            'Example Layup B' => [[1, 30, 1200, 0], [2, 30, 1200, 90]],
        ];

        foreach ($examples as $name => $layerRows) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($name);
            $sheet->fromArray(['layer_order', 'thickness', 'width', 'angle'], null, 'A1', true);
            $sheet->fromArray($layerRows, null, 'A2', true);
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'clt_template_') . '.xlsx';
        (new XlsxWriter($spreadsheet))->save($tmpPath);

        $contents = file_get_contents($tmpPath);
        unlink($tmpPath);

        return response($contents, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="import-template.xlsx"',
        ]);
    }
}
