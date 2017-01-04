<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalMember extends Model
{
    protected $fillable = ['member_id', 'name', 'affiliation'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
