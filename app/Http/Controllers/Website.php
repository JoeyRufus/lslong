<?php

namespace App\Http\Controllers;

use App\Models\WebsiteCtgrModel;
use App\Models\WebsiteModel;
use Illuminate\Http\Request;

class Website extends Controller
{
    public function index()
    {
        $website = WebsiteCtgrModel::with(['website' => function ($q) {
            $q->orderBy('click_count', 'desc');
        }])->get();
        return view('website.index', ['website' => $website]);
    }
    // 数据保存
    public function store(Request $request)
    {
        $data = $request->post();
        $genre = WebsiteCtgrModel::firstOrCreate(['title' => $data['genre']]);
        $data['website_ctgr_id'] = $genre->id;
        $data['url'] = strpos($data['url'], 'http') === 0 ? $data['url'] : 'http://' . $data['url'];
        $r = WebsiteModel::create($data);
        return response()->json([
            'code' => '200',
            'msg' => '添加成功~',
        ]);
    }
    // 数据删除
    public function del($id)
    {
        WebsiteModel::destroy($id);
    }
    // 更新点击次数
    public function clickInc($id)
    {
        WebsiteModel::where('id', $id)->increment('click_count');
    }

    // 通过URL获取网页标题、图标、描述
    public function getUrlInfo($url)
    {
        return Website::ManageUrlInfo($url);
    }

    // 处理数据
    public function ManageUrlInfo($url)
    {
        $content = Website::ConnectUrl($url);
        $output = $content['output'];
        $curl_info = $content['curl_info'];
        $page_info = array();
        $page_info['url'] = $curl_info['url'];
        $page_info['title'] = '';
        $page_info['description'] = '';
        $page_info['icon_href'] = '';
        if (empty($output)) {
            return $page_info;
        }
        // 获取网页编码，把非utf-8网页编码转成utf-8，防止网页出现乱码
        $meta_content_type = '';
        if (isset($curl_info['content_type']) && strstr($curl_info['content_type'], "charset=") != "") {
            $meta_content_type = explode("charset=", $curl_info['content_type'])[1];
        }
        if ($meta_content_type == '') {
            preg_match('/<META\s+http-equiv="Content-Type"\s+content="([\w\W]*?)"/si', $output, $matches); // 中文编码，如 http://www.qq.com
            if (empty($matches[1])) {
                preg_match('/<META\s+content="([\w\W]*?)"\s+http-equiv="Content-Type"/si', $output, $matches);
            }
            if (empty($matches[1])) {
                preg_match('/<META\s+charset="([\w\W]*?)"/si', $output, $matches); // 特殊字符编码，如 http://www.500.com
            }
            if (!empty($matches[1]) && strstr($matches[1], "charset=") != "") {
                $meta_content_type = explode("charset=", $matches[1])[1];
            }
        }
        if (!in_array(strtolower($meta_content_type), array('', 'utf-8', 'utf8'))) {
            $output = mb_convert_encoding($output, "utf-8", $meta_content_type); // gbk, gb2312
        }
        // 若网页仍然有乱码，有乱码则gbk转utf-8
        if (json_encode($output) == '' || json_encode($output) == null) {
            $output = mb_convert_encoding($output, "utf-8", 'gbk');
        }
        // Title
        preg_match('/<TITLE>([\w\W]*?)<\/TITLE>/si', $output, $matches);
        if (!empty($matches[1])) {
            $page_info['title'] = $matches[1];
        }
        // Icon
        preg_match('/<LINK\s+rel="shortcut\sicon"\s+href="([\w\W]*?)"/si', $output, $matches);
        if (!empty($matches[1])) {
            $page_info['icon_href'] = $matches[1];
        }
        if (strpos($page_info['icon_href'], 'http') === false) {
            $page_info['icon_href'] = "https://api.iowen.cn/favicon/" . $url . ".png";
        }
        // Description
        preg_match('/<META\s+name="description"\s+content="([\w\W]*?)"/si', $output, $matches);
        if (!empty($matches[1])) {
            $page_info['description'] = $matches[1];
        }
        return $page_info;
    }
    // 初始化url连接
    public function ConnectUrl($url, $timeout = 5, $conntimeout = 3)
    {
        $ch = curl_init();
        $header = array();
        array_push($header, 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
        array_push($header, 'Referer:' . $url);
        array_push($header, 'host:' . $url);
        array_push($header, 'accept:  text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8');
        array_push($header, 'upgrade-insecure-requests:1');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // HTTP 头中的 "Location: "重定向
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 字符串返回
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 1); // 0表示不输出Header，1表示输出
        curl_setopt($ch, CURLOPT_NOBODY, 0); // 0表示不输出Body，1表示输出
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conntimeout); // 尝试连接时等待的秒数。设置为0，则无限等待
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout + 5); // 允许 cURL 函数执行的最长秒数
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        curl_close($ch);
        return ['output' => $output, 'curl_info' => $curl_info];
    }
}
