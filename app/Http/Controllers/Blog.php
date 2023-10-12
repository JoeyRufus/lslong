<?php

namespace App\Http\Controllers;

use App\Models\BlogCtgrModel;
use App\Models\BlogModel;
use Illuminate\Http\Request;

class Blog extends Controller
{
    public function store(Request $request)
    {
        $data = $request->post();
        $genre = BlogCtgrModel::firstOrCreate(['title' => $data['tiny_genre']]);
        $data['blog_ctgr_id'] = $genre->id;
        $r = BlogModel::create($data);
        return response()->json([
            'code' => '200',
            'msg' => '添加成功~',
        ]);
    }
    public function item()
    {
        $str = implode(',', $array); //implode：崩溃，使内爆；
        $array = explode(',', $str); //explode：爆炸
    }
    public function update(Request $request)
    {
        $data = $request->post();
        $genre = BlogCtgrModel::firstOrCreate(['title' => $data['tiny_genre']]);
        $blog = array(
            'title' => $data['title'],
            'content' => $data['content'],
            'blog_ctgr_id' => $genre->id,
        );
        BlogModel::where('id', $data['id'])->update($blog);
        //$r = BlogModel::where('id', $data['id'])->update($data);
        return response()->json([
            'code' => '200',
            'msg' => '更新成功~',
        ]);

    }
    public function getBlogAll()
    {
        $blog = BlogModel::orderBy('updated_at', 'desc')->get();
        $i = 0;
        foreach ($blog as $v) {
            $blog[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return $blog;
    }
    public function getBlogByGenre($genreId)
    {
        if ($genreId) {
            $blog = BlogModel::where('blog_ctgr_id', $genreId)->orderBy('updated_at', 'desc')->get();
        } else {
            $blog = BlogModel::orderBy('updated_at', 'desc')->get();
        }
        $i = 0;
        foreach ($blog as $v) {
            $blog[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return $blog;
    }
    public function getBlogById($id)
    {
        $blog = BlogModel::with('blogCtgr')->find($id);
        $blog['genre'] = $blog->blogCtgr->title;
        return $blog;
    }
    public function del($id)
    {
        BlogModel::destroy($id);
    }
}
