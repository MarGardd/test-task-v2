<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

}
