<?php

namespace App\Http\Controllers;

use App\Models\ExperienceLabelModel;
use App\Models\ExperienceModel;
use Illuminate\Http\Request;

class Experience extends Controller
{
    public function index()
    {
        $last = ExperienceModel::select('id', 'title')->orderBy('updated_at', 'desc')->limit(10)->get();
        $label = ExperienceLabelModel::withCount('experience')->get();
        $count = ExperienceModel::count();
        $exp = self::getExpByLabel(0);
        return view('exp.index', ['exp' => $exp, 'last' => $last, 'label' => $label, 'count' => $count]);
    }
    public function detail($id)
    {
        $exp = self::getExpById($id);
        $exp['label'] = substr($exp['label'], 0, -1);
        return view('exp.detail', ['exp' => $exp]);
    }
    public function store(Request $request)
    {
        $data = $request->post();
        $label_id = self::StoreLabel($data['label']);
        $experience = ExperienceModel::create($data);
        $experience->experienceLabel()->sync($label_id);
        return response()->json([
            'code' => '200',
            'msg' => '添加成功~',
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->post();
        $label_id = self::StoreLabel($data['label']);
        $experience = ExperienceModel::updateOrCreate(['id' => $data['id']], [
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
        $experience->experienceLabel()->sync($label_id);
        return response()->json([
            'code' => '200',
            'msg' => '更新成功~',
        ]);
    }

    public function StoreLabel($label)
    {
        $label = explode('|', $label);
        $label = array_filter($label);
        foreach ($label as $v) {
            $R = ExperienceLabelModel::firstOrCreate(['title' => $v]);
            $label_id[] = $R->id;
        }
        return $label_id;
    }

    public function getExpByLabel($labelId, $page = 1)
    {

        if ($labelId) {
            $label = ExperienceLabelModel::with(['experience' => function ($q) {
                $q->orderBy('updated_at', 'desc');
            }])->find($labelId);
            $exp = $label->experience()->paginate(10, ['*'], 'page', $page);
        } else {
            // $labelId==0时，切换经验面板查询所有exp
            $exp = ExperienceModel::orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        }
        $i = 0;
        foreach ($exp as $v) {
            $exp[$i]['content'] = strip_tags($v->content);
            $i++;
        }
        return $exp;
    }

    public function getExpById($id)
    {
        $exp = ExperienceModel::with('experienceLabel')->find($id);
        $label = '';
        foreach ($exp->experienceLabel as $v) {
            $label .= $v->title . '|';
        }
        $exp['label'] = $label;
        return $exp;
    }

    public function del($id)
    {
        $exp = ExperienceModel::find($id);
        $exp->experienceLabel()->detach();
        ExperienceModel::destroy($id);
    }
}
