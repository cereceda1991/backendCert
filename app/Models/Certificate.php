<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    
    protected $connection = 'mongodb';
    protected $collection = 'certificates';

    protected $fillable = [
        '_id'.
        'certificateType',
        'id_user',
        'id_template',
        'authority1',
        'authority2',
        'career_type',
        'certificateContent',
        'urlLogo'
    ];

    protected $casts = [
        '_id' => 'string',
        'certificateType' => 'string',
        'id_user' => 'string',
        'id_template' => 'string',
        'authority1' => 'string',
        'authority2' => 'string',
        'career_type' => 'string',
        'certificateContent' => 'string',
        'urlLogo' => 'string'
    ];

}
