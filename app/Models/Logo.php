<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'logos';

    protected $fillable = [
        'urlImg',
        'publicId',
        'name',
        'status'
    ];
    protected $casts = [
        'urlImg' => 'string',
        'publicId'=>'string',
        'name' => 'string',
        'status' => 'boolean'
    ];
}
