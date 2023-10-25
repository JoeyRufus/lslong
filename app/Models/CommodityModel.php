<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityModel extends Model
{
    use HasFactory;
    protected $table = 'commodity';
    protected $fillable = ['title', 'genre', 'mark', 'price', 'weight', 'min', 'max'];
}
