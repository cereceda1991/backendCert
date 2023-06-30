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
        'subjectName',
        'certificateType',
        'issuingAuthority',
        'expiryDate',
        'publicKey',
        'president',
        'academicDirector',
        'certificateContent',
        'created_by',
    ];

    protected $casts = [
        'president' => 'array',
        'academicDirector' => 'array',
    ];
}
