<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'country',
    ];

    protected $appends = ['base64'];

    public function getBase64Attribute()
    {
        $index = $this->attributes['id'];
        $path = 'faces' . DIRECTORY_SEPARATOR . $index . '.png';
        $contents = Storage::get($path);
        return base64_encode($contents);
    }
}
