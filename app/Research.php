<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Research extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'propose_id',
        'file_progress_activity',
        'file_progress_activity_ori',
        'file_progress_budgets',
        'file_progress_budgets_ori',
        'file_final_activity',
        'file_final_activity_ori',
        'file_final_budgets',
        'file_final_budgets_ori',
        'created_by',
    ];
    protected $dates = ['deleted_at'];

    public function propose()
    {
        return $this->belongsTo(Propose::class);
    }

    public function researchOutputGeneral()
    {
        return $this->hasMany(ResearchOutputGeneral::class);
    }

    public function researchOutputRevision()
    {
        return $this->hasMany(ResearchOutputRevision::class);
    }
}
