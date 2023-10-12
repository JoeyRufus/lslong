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
    public function getBlogAll($page = 1)
    {
        $blog = BlogModel::orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        $i = 0;
        foreach ($blog as $v) {
            $blog[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return $blog;
    }
    public function getBlogByGenre($genreId, $page = 1)
    {
        if ($genreId) {
            $blog = BlogModel::where('blog_ctgr_id', $genreId)->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        } else {
            $blog = BlogModel::orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        }
        $i = 0;
        foreach ($blog as $v) {
            $blog[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        //dd($blog);
        return $blog;
    }
    public function getBlogById($id)
    {
        $blog = BlogModel::with('blogCtgr')->find($id);
        $blog['genre'] = $blog->blogCtgr->title;
        return $blog;
    }
    public function getBlogByTitle($title, $page = 1)
    {
        $blog = BlogModel::where('title', 'like', "%$title%")->get();
        $i = 0;
        foreach ($blog as $v) {
            $blog[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return $blog;
    }
    public function del($id)
    {
        BlogModel::destroy($id);
    }
}
