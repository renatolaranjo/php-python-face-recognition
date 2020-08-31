<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class User extends Model
{
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
