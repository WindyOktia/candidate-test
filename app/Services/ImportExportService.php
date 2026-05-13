<?php

namespace App\Services;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Contracts\Services\ImportExportServiceInterface;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ImportExportService implements ImportExportServiceInterface
{
    public function __construct(
        private readonly LayupRepositoryInterface $layupRepository,
        private readonly LayerRepositoryInterface $layerRepository,
    ) {}

    public function exportSupplier(Supplier $supplier): array
    {
        $supplier->loadMissing(['layups.layers' => fn ($q) => $q->orderBy('layer_order')]);

        $rows = [];
        foreach ($supplier->layups as $layup) {
            foreach ($layup->layers as $layer) {
                $rows[] = [
                    'layup_name'  => $layup->name,
                    'layer_order' => $layer->layer_order,
                    'thickness'   => $layer->thickness,
                    'width'       => $layer->width,
                    'angle'       => $layer->angle,
                ];
            }
        }

        return $rows;
    }

    public function detectConflicts(Supplier $supplier, array $importData): array
    {
        $conflicts = [];

        foreach ($importData['layups'] ?? [] as $layupData) {
            $existingLayup = $this->layupRepository->findByNameAndSupplier($layupData['name'], $supplier->id);

            if (! $existingLayup) {
                continue;
            }

            foreach ($layupData['layers'] ?? [] as $layerData) {
                $existingLayer = $this->layerRepository->findByOrderAndLayup($layerData['layer_order'], $existingLayup->id);

                if (! $existingLayer) {
                    continue;
                }

                $diffFields = [];
                foreach (['thickness', 'width', 'angle'] as $field) {
                    if ((float) $existingLayer->$field !== (float) $layerData[$field]) {
                        $diffFields[$field] = [
                            'existing' => $existingLayer->$field,
                            'incoming' => $layerData[$field],
                        ];
                    }
                }

                if (! empty($diffFields)) {
                    $conflicts[] = [
                        'layup_name'    => $layupData['name'],
                        'layup_id'      => $existingLayup->id,
                        'layer_order'   => $layerData['layer_order'],
                        'layer_id'      => $existingLayer->id,
                        'differences'   => $diffFields,
                        'existing_layer' => [
                            'layer_order' => $existingLayer->layer_order,
                            'thickness'   => $existingLayer->thickness,
                            'width'       => $existingLayer->width,
                            'angle'       => $existingLayer->angle,
                        ],
                        'incoming_layer' => [
                            'layer_order' => $layerData['layer_order'],
                            'thickness'   => $layerData['thickness'],
                            'width'       => $layerData['width'],
                            'angle'       => $layerData['angle'],
                        ],
                    ];
                }
            }
        }

        return $conflicts;
    }

    public function importSupplier(Supplier $supplier, array $importData, string $strategy, bool $dryRun = false): array
    {
        $summary = [];
        try {
            DB::transaction(function () use ($supplier, $importData, $strategy, $dryRun, &$summary) {
                foreach ($importData['layups'] ?? [] as $layupData) {
                    $added   = 0;
                    $updated = 0;
                    $skipped = 0;

                    $existing = $this->layupRepository->findByNameAndSupplier($layupData['name'], $supplier->id);
                    $isNew    = ! $existing;

                    if (! $existing) {
                        $layup = $this->layupRepository->create([
                            'supplier_id' => $supplier->id,
                            'name'        => $layupData['name'],
                        ]);
                    } elseif ($strategy === 'duplicate') {
                        $layup = $this->layupRepository->create([
                            'supplier_id' => $supplier->id,
                            'name'        => $layupData['name'] . ' (imported)',
                        ]);
                        $isNew = true;
                    } else {
                        $layup = $existing;
                    }

                    foreach ($layupData['layers'] ?? [] as $layerData) {
                        $existingLayer = $this->layerRepository->findByOrderAndLayup($layerData['layer_order'], $layup->id);

                        if (! $existingLayer) {
                            $this->layerRepository->create(array_merge($layerData, ['layup_id' => $layup->id]));
                            $added++;
                            continue;
                        }

                        if ($strategy === 'overwrite') {
                            $this->layerRepository->update($existingLayer, [
                                'thickness' => $layerData['thickness'],
                                'width'     => $layerData['width'],
                                'angle'     => $layerData['angle'],
                            ]);
                            $updated++;
                        } elseif ($strategy === 'skip') {
                            $skipped++;
                        } elseif ($strategy === 'reject') {
                            throw new \RuntimeException('Import rejected due to conflicts.');
                        } elseif ($strategy === 'duplicate') {
                            $this->layerRepository->create(array_merge($layerData, ['layup_id' => $layup->id]));
                            $added++;
                        } else {
                            $skipped++;
                        }
                    }

                    $summary[] = [
                        'name'           => $layupData['name'],
                        'is_new'         => $isNew,
                        'layers_added'   => $added,
                        'layers_updated' => $updated,
                        'layers_skipped' => $skipped,
                    ];
                }

                if ($dryRun) {
                    throw new \LogicException('__dry_run_rollback__');
                }
            });
        } catch (\LogicException $e) {
            if ($e->getMessage() !== '__dry_run_rollback__') {
                throw $e;
            }
        }

        return $summary;
    }

    public function importSupplierWithResolutions(Supplier $supplier, array $importData, array $resolutions, bool $dryRun = false): array
    {
        $summary = [];
        try {
            DB::transaction(function () use ($supplier, $importData, $resolutions, $dryRun, &$summary) {
                foreach ($importData['layups'] ?? [] as $layupData) {
                    $added   = 0;
                    $updated = 0;
                    $skipped = 0;

                    $existing = $this->layupRepository->findByNameAndSupplier($layupData['name'], $supplier->id);
                    $isNew    = ! $existing;
                    $layup    = $existing ?? $this->layupRepository->create([
                        'supplier_id' => $supplier->id,
                        'name'        => $layupData['name'],
                    ]);

                    foreach ($layupData['layers'] ?? [] as $layerData) {
                        $existingLayer = $this->layerRepository->findByOrderAndLayup($layerData['layer_order'], $layup->id);

                        if (! $existingLayer) {
                            $this->layerRepository->create(array_merge($layerData, ['layup_id' => $layup->id]));
                            $added++;
                            continue;
                        }

                        $key        = $layupData['name'] . '_' . $layerData['layer_order'];
                        $resolution = $resolutions[$key] ?? 'skip';

                        if ($resolution === 'incoming') {
                            $this->layerRepository->update($existingLayer, [
                                'thickness' => $layerData['thickness'],
                                'width'     => $layerData['width'],
                                'angle'     => $layerData['angle'],
                            ]);
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    }

                    $summary[] = [
                        'name'           => $layupData['name'],
                        'is_new'         => $isNew,
                        'layers_added'   => $added,
                        'layers_updated' => $updated,
                        'layers_skipped' => $skipped,
                    ];
                }

                if ($dryRun) {
                    throw new \LogicException('__dry_run_rollback__');
                }
            });
        } catch (\LogicException $e) {
            if ($e->getMessage() !== '__dry_run_rollback__') {
                throw $e;
            }
        }

        return $summary;
    }
}
