<?php

namespace App\Http\Controllers;

use App\Models\BlogCtgrModel;
use App\Models\BlogModel;
use App\Models\ExperienceLabelModel;
use App\Models\ExperienceModel;
use App\Models\WebsiteCtgrModel;
use App\Models\WebsiteModel;

class Index extends Controller
{
    public function getIndexInfo()
    {
        $website_ctgr = WebsiteCtgrModel::get();
        $website = WebsiteModel::orderBy('click_count', 'desc')->limit(10)->get();
        // 主体list
        $blog = Blog::getBlogAll();
        $blog_ctgr = BlogCtgrModel::withCount('blog')->get();
        $blog_last = BlogModel::select('id', 'title')->orderBy('updated_at', 'desc')->limit(10)->get();
        $exp_last = ExperienceModel::select('id', 'title')->orderBy('updated_at', 'desc')->limit(10)->get();
        $exp_label = ExperienceLabelModel::withCount('experience')->get();
        return view('index', ['website' => $website, 'website_ctgr' => $website_ctgr,
            'blog_ctgr' => $blog_ctgr, 'blog' => $blog, 'blog_last' => $blog_last,
            'exp_label' => $exp_label, 'exp_last' => $exp_last]);
    }

    public function getExpInfo()
    {
        $exp_last = ExperienceModel::select('id', 'title')->orderBy('updated_at', 'desc')->limit(10)->get();
        $exp_label = ExperienceLabelModel::withCount('experience')->get();
        return response()->json(['last' => $exp_last, 'label' => $exp_label]);
    }
}
