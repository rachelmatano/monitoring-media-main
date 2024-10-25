<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class MediaProve extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;
    protected $timestamp = true;
    protected $table = 'media_prove';
    protected $fillable = [
        'id','title','link','tipe',
        'project_id','media_id','reporter_id',
        'date_posted'
    ];
}
