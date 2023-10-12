<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCtgrModel extends Model
{
    use HasFactory;
    protected $table = 'blog_ctgr';
    protected $fillable = ['title'];
    public function blog(): HasMany
    {
        return $this->hasMany(BlogModel::class, 'blog_ctgr_id');
    }
}
