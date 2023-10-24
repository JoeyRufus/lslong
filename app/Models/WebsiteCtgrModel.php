<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCtgrModel extends Model
{
    use HasFactory;
    protected $table = 'website_ctgr';
    protected $fillable = ['title'];

    public function website()
    {
        return $this->hasMany('App\Models\WebsiteModel', 'website_ctgr_id');
    }
}
