<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ProveGallery extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;
    protected $timestamp = true;
    protected $table = 'prove_gallery';
    protected $fillable = [
        'id','prove_id','link_path',
        'tipe'
    ];
}
