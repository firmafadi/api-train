<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    use HasFactory;
    protected $table = 'employee';
    protected $fillable = [
        'full_name',
        'address',
        'no_handphone',
        'join_date',
        'salary'
    ];
}
