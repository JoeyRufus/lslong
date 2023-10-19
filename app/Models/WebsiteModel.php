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

    // 根据分类ID获取web
    public function GetWebsiteByGenre($genreId)
    {
        /* $website = WebsiteModel::where('website_ctgr_id', $genreId)->get();
        return $website; */
        return WebsiteModel::where('website_ctgr_id', $genreId)->get();
    }
    // 根据规则获取web
    public function GetWebsiteByRule($rule)
    {
        /* if ($rule == "click_num") {
        $website = self::order('click_count', 'desc');
        } else {
        $website = self::order('updated_at', 'desc');
        }
        return $website->limit(14)->get(); */
        return WebsiteModel::orderBy($rule, 'desc')->limit(14)->get();
    }
}
