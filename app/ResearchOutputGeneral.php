<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearchOutputGeneral extends Model
{
    protected $fillable = ['research_id', 'item', 'file_name_ori', 'file_name', 'output_description'];

    public function research()
    {
        return $this->belongsTo(Research::class);
    }

    public function outputMember()
    {
        return $this->hasMany(OutputMember::class, 'output_id', 'id');
    }
}
