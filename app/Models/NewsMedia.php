<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class NewsMedia extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;
    protected $timestamp = true;
    protected $table = 'media_news';
    protected $fillable = [
        'id','m_name','email',
        'logo','address','phone_no','ref_code'
    ];
}
