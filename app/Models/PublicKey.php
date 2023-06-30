<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class PublicKey extends Model
{
    protected $connection = 'mongodb'; 
    protected $collection = 'public_keys'; 

    protected $fillable = ['public_key'];
}
