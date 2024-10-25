<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ApprovedMedia extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    protected $timestamp = true;
    protected $table = 'approved_media';
    protected $fillable = [
        'id','period','media_id',
        'printed_by_project','printed_general',
        'online_by_project','online_general',
        'printed_total','online_total',
        'created_by','updated_by'
    ];
}
