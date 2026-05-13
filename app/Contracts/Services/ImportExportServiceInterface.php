<?php

namespace App\Contracts\Services;

use App\Models\Supplier;

interface ImportExportServiceInterface
{
    public function exportSupplier(Supplier $supplier): array;
    public function detectConflicts(Supplier $supplier, array $importData): array;
    public function importSupplier(Supplier $supplier, array $importData, string $strategy, bool $dryRun = false): array;
    public function importSupplierWithResolutions(Supplier $supplier, array $importData, array $resolutions, bool $dryRun = false): array;
}
