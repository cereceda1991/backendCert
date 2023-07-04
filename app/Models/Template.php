<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'templates';

    protected $fillable = [
        'urlImg',
        'name'
    ];
    protected $casts = [
        'urlImg' => 'string',
        'name' => 'string',
    ];
}
