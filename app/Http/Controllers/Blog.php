<?php

namespace App\Http\Controllers;

use App\Models\BlogCtgrModel;
use App\Models\BlogModel;
use Illuminate\Http\Request;

class Blog extends Controller
{
    public function index()
    {
        $last = BlogModel::select('id', 'title')->orderBy('updated_at', 'desc')->limit(10)->get();
        $ctgr = BlogCtgrModel::withCount('blog')->get();
        $count = BlogModel::count();
        $blog = self::getBlogAll();
        return view('blog.index', ['count' => $count, 'last' => $last, 'blog' => $blog, 'genre' => $ctgr]);
    }

    public function store(Request $request)
    {
        $data = $request->post();
        $genre = BlogCtgrModel::firstOrCreate(['title' => $data['genre']]);
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
        $genre = BlogCtgrModel::firstOrCreate(['title' => $data['genre']]);
        $blog = array(
            'title' => $data['title'],
            'content' => $data['content'],
            'blog_ctgr_id' => $genre->id,
        );
        BlogModel::where('id', $data['id'])->update($blog);
        return response()->json([
            'code' => '200',
            'msg' => '更新成功~',
        ]);

    }

    public function detail($id)
    {
        $blog = BlogModel::with('blogCtgr')->find($id);
        $blog['genre'] = $blog->blogCtgr->title;
        return view('blog.detail', ['blog' => $blog]);
    }

    public function del($id)
    {
        BlogModel::destroy($id);
    }
    // 按照分页获取数据
    public function getBlogAll($page = 1)
    {
        $blog = BlogModel::orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        return Blog::ManageBlogContent($blog);
    }
    // 点击左侧菜单栏时切换
    public function getBlogByGenre($genreId, $page = 1)
    {
        if ($genreId) {
            $blog = BlogModel::where('blog_ctgr_id', $genreId)->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
            return Blog::ManageBlogContent($blog);
        } else {
            return Blog::getBlogAll($page);
        }

    }
    // 编辑时通过id获取数据
    public function getBlogById($id)
    {
        $blog = BlogModel::with('blogCtgr')->find($id);
        $blog['genre'] = $blog->blogCtgr->title;
        return $blog;
    }
    // 根据title搜索获取数据
    public function getBlogByTitle($title, $page = 1)
    {
        $blog = BlogModel::where('title', 'like', "%$title%")->get();
        return Blog::ManageBlogContent($blog);
    }
    // 处理数据显示摘要
    public function ManageBlogContent($blog)
    {
        $i = 0;
        foreach ($blog as $v) {
            $blog[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return $blog;
    }
}
