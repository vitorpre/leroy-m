<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $fillable = ['lm', 'name', 'free_shipping', 'description', 'price'];
    use SoftDeletes;
    //
}
