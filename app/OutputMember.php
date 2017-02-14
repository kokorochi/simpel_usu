<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputMember extends Model
{
    protected $fillable = ['output_id', 'nidn', 'external'];

    public function researchOutputGeneral()
    {
        return $this->belongsTo(ResearchOutputGeneral::class, 'output_id', 'id');
    }
}
