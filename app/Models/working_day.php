<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class working_day extends Model
{
    use HasFactory;
    protected $table = 'working_days';
    protected $fillable = [
        'working_day',
        'working_day_code'
    ];

}
