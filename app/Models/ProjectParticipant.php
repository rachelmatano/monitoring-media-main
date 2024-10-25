<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ProjectParticipant extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;
    protected $timestamp = true;
    protected $table = 'project_participant';
    protected $fillable = [
        'id','media_id','project_id','reporter_id'
    ];
}
