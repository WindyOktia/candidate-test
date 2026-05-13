<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layup extends Model
{
    use HasFactory;

    protected $table = 'clt_layups';

    protected $fillable = [
        'supplier_id',
        'name',
        'description',
        'status',
        'revision_count',
        'revisions',
    ];

    protected $casts = [
        'revision_count' => 'integer',
        'revisions'      => 'array',
    ];

    public function revisionLabel(): string
    {
        if ($this->revision_count === 0) {
            return 'Initial';
        }
        $last = collect($this->revisions ?? [])->last();
        $date = $last ? $last['date'] : now()->format('M j');
        return 'Rev ' . $this->revision_count . ' (' . $date . ')';
    }

    public function bumpRevision(string $note = ''): void
    {
        $revisions   = $this->revisions ?? [];
        $count       = $this->revision_count + 1;
        $revisions[] = [
            'rev'  => $count,
            'date' => now()->format('M j'),
            'note' => $note,
        ];
        $this->update([
            'revision_count' => $count,
            'revisions'      => $revisions,
        ]);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function layers(): HasMany
    {
        return $this->hasMany(Layer::class)->orderBy('layer_order');
    }
}
