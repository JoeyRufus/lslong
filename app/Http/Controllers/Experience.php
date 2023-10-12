<?php

namespace App\Http\Controllers;

use App\Models\ExperienceLabelModel;
use App\Models\ExperienceModel;
use Illuminate\Http\Request;

class Experience extends Controller
{
    public function store(Request $request)
    {
        $data = $request->post();
        $label = explode('|', $data['label']);
        $label = array_filter($label);
        foreach ($label as $v) {
            $R = ExperienceLabelModel::firstOrCreate(['title' => $v]);
            $label_id[] = $R->id;
        }
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
        $label = explode('|', $data['label']);
        $label = array_filter($label);
        foreach ($label as $v) {
            $R = ExperienceLabelModel::firstOrCreate(['title' => $v]);
            $label_id[] = $R->id;
        }
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
    public function getExpByLabel($labelId)
    {
        if ($labelId) {
            $label = ExperienceLabelModel::with(['experience' => function ($q) {
                $q->orderBy('updated_at', 'desc');
            }])->find($labelId);
            $exp = $label->experience;
        } else {
            // $labelId==0时，切换经验面板查询所有exp
            $exp = ExperienceModel::orderBy('updated_at', 'desc')->get();
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