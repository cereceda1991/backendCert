<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Firm extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'firms';

    protected $fillable = [
        'urlImg',
        'publicId',
        'autority',
        'name',
        'status',
        'id_user'
    ];
    protected $casts = [
        'urlImg' => 'string',
        'publicId' => 'string',
        'autority'=>'string',
        'name' => 'string',
        'status' => 'boolean',
        'id_user' => 'string'
    ];
}