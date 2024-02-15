<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    use HasFactory;

    protected $table = 'book';
    protected $fillable = [
        'title',
        'author',
        'release_date',
        'publisher',
        'price'
    ];
}
