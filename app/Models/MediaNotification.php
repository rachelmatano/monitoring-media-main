<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class MediaNotification extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    protected $timestamp = true;
    protected $table = 'media_notification';
    protected $fillable = [
        'id','notif_time','title',
        'content','category',//Project or Information
        'status','tipe',//Public or Private
        'media_id'
    ];
}
