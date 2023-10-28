<?php

namespace App\Http\Controllers;

use App\Models\BlogModel;
use App\Models\ExperienceModel;
use App\Models\WebsiteModel;

class Index extends Controller
{
    public function home()
    {
        $website = WebsiteModel::orderBy('click_count', 'desc')->limit(10)->get();
        $blog = BlogModel::orderBy('updated_at', 'desc')->limit(10)->get();
        $blog = Blog::ManageBlogContent($blog);
        $exp = ExperienceModel::orderBy('updated_at', 'desc')->limit(10)->get();
        $i = 0;
        foreach ($exp as $v) {
            $exp[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return view('home', ['website' => $website, 'blog' => $blog, 'exp' => $exp]);
    }
}
