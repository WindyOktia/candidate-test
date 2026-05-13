<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layer extends Model
{
    use HasFactory;

    protected $table = 'clt_layers';

    protected $fillable = [
        'layup_id',
        'layer_order',
        'thickness',
        'width',
        'angle',
        'species',
        'grade',
    ];

    protected $casts = [
        'layer_order' => 'integer',
        'thickness'   => 'float',
        'width'       => 'float',
        'angle'       => 'float',
    ];

    public function layup(): BelongsTo
    {
        return $this->belongsTo(Layup::class);
    }
}
