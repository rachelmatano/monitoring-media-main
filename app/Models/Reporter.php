<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notification\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;



class Reporter extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    use HasApiTokens;
    protected $timestamp = true;
    protected $table = 'reporter';
    protected $fillable = [
        'id','email',
        'name','code','gender',
        'phone_no','photo','password','dob'
    ];
    protected $hidden = [
        "password"
    ];
    protected $guarded =['id'];
    public function getAuthPassword(){
        return $this->password;
    }
}
