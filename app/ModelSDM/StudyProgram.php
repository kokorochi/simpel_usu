<?php

namespace App\ModelSDM;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $connection = 'simsdm';
    protected $table = 'study_program';
}
