<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteModel extends Model
{
    use HasFactory;
    protected $table = 'website';
    protected $fillable = ['title', 'url', 'icon_href', 'description', 'website_ctgr_id'];
    public function websiteCtgr()
    {
        return $this->belongsTo('App\Model\WebsiteCtgrModel', 'website_ctgr_id');
    }
}
