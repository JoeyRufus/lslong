<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    use HasFactory;
    protected $table = 'blog';
    protected $fillable = ['title', 'content', 'blog_ctgr_id'];

    public function blogCtgr()
    {
        return $this->belongsTo(BlogCtgrModel::class, 'blog_ctgr_id');
    }
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
