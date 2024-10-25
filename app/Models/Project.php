<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Project extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;
    protected $timestamp = true;
    protected $table = 'project';
    protected $fillable = [
        'id','date_posted','title','content',
        'valid_until','minimum'
    ];
}
