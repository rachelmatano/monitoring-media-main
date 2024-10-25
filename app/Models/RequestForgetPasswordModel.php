<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class RequestForgetPasswordModel extends Model
{
    use HasFactory;
    use Uuid, SoftDeletes;
    public $timestamp = true;
    protected $table = "request_forget_password";
    protected $fillable = [
        'id','user_id','email','tipe','status','token'
    ];
}
