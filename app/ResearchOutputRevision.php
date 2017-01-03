<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearchOutputRevision extends Model
{
    protected $fillable = [
        'research_id',
        'item',
        'revision_text',
        'created_by',
        'updated_by',
    ];

    protected $touches = ['research'];

    public function research()
    {
        return $this->belongsTo(Research::class);
    }
}
