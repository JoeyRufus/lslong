<?php

namespace App\Http\Controllers;

use App\Models\WebsiteCtgrModel;
use App\Models\WebsiteModel;
use Illuminate\Http\Request;

class Website extends Controller
{

    public function store(Request $request)
    {
        $data = $request->post();
        $genre = WebsiteCtgrModel::firstOrCreate(['title' => $data['genre']]);
        $data['website_ctgr_id'] = $genre->id;
        $data['url'] = strpos($data['url'], 'http') ? $data['url'] : 'http://' . $data['url'];
        $r = WebsiteModel::create($data);
        return response()->json([
            'code' => '200',
            'msg' => '添加成功~',
        ]);
    }
    public function clickInc($id)
    {
        WebsiteModel::where('id', $id)->increment('click_count');
    }
    // 根据分类ID获取web数
    public function getWebsiteByGenre($genreId)
    {
        if ($genreId == 0) {
            $website = WebsiteModel::orderBy('updated_at', 'desc')->limit(10)->get();
        } else if ($genreId == -1) {
            $website = WebsiteModel::orderBy('click_count', 'desc')->limit(10)->get();
        } else {
            $website = WebsiteModel::getWebsiteByGenre($genreId);
        }
        return $website;
    }
    public function del($id)
    {
        WebsiteModel::destroy($id);
    }

    // 通过url获取页面内容
    public function get_siteurl_curlinfo($url, $timeout = 5, $conntimeout = 3)
    {
        $ch = curl_init();
        $url_host = explode("/", $url)[0];
        $header = array();
        array_push($header, 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
        array_push($header, 'Referer:' . $url);
        array_push($header, 'host:' . $url_host);
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
        $page_info = Website::get_page_info($output, $curl_info);
        if (strpos($page_info['icon_href'], 'http') === false) {
            $page_info['icon_href'] = "https://api.iowen.cn/favicon/" . $url . ".png";
        }
        $result = array('url' => $curl_info['url'], 'title' => $page_info['site_title'], 'description' => $page_info['site_description'], 'icon_href' => $page_info['icon_href']);
        return $result;
    }
    // 正则匹配处理页面
    public function get_page_info($output, $curl_info = array())
    {
        $page_info = array();
        $page_info['site_title'] = '';
        $page_info['site_description'] = '';
        $page_info['site_keywords'] = '';
        $page_info['friend_link_status'] = 0;
        $page_info['site_claim_status'] = 0;
        $page_info['site_home_size'] = 0;
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

        $page_info['site_home_size'] = strlen($output);

        # Title
        preg_match('/<TITLE>([\w\W]*?)<\/TITLE>/si', $output, $matches);
        if (!empty($matches[1])) {
            $page_info['site_title'] = $matches[1];
        }

        #icon
        preg_match('/<LINK\s+rel="shortcut\sicon"\s+href="([\w\W]*?)"/si', $output, $matches);
        if (!empty($matches[1])) {
            $page_info['icon_href'] = $matches[1];
        }

        // 正则匹配，获取全部的meta元数据
        preg_match_all('/<META(.*?)>/si', $output, $matches);
        $meta_str_array = $matches[0];

        $meta_array = array();
        $meta_array['description'] = '';
        $meta_array['keywords'] = '';

        foreach ($meta_str_array as $meta_str) {
            preg_match('/<META\s+name="([\w\W]*?)"\s+content="([\w\W]*?)"/si', $meta_str, $res);
            if (!empty($res)) {
                $meta_array[strtolower($res[1])] = $res[2];
            }

            preg_match('/<META\s+content="([\w\W]*?)"\s+name="([\w\W]*?)"/si', $meta_str, $res);
            if (!empty($res)) {
                $meta_array[strtolower($res[2])] = $res[1];
            }

            preg_match('/<META\s+http-equiv="([\w\W]*?)"\s+content="([\w\W]*?)"/si', $meta_str, $res);
            if (!empty($res)) {
                $meta_array[strtolower($res[1])] = $res[2];
            }

            preg_match('/<META\s+content="([\w\W]*?)"\s+http-equiv="([\w\W]*?)"/si', $meta_str, $res);
            if (!empty($res)) {
                $meta_array[strtolower($res[2])] = $res[1];
            }

            preg_match('/<META\s+scheme="([\w\W]*?)"\s+content="([\w\W]*?)"/si', $meta_str, $res);
            if (!empty($res)) {
                $meta_array[strtolower($res[1])] = $res[2];
            }

            preg_match('/<META\s+content="([\w\W]*?)"\s+scheme="([\w\W]*?)"/si', $meta_str, $res);
            if (!empty($res)) {
                $meta_array[strtolower($res[2])] = $res[1];
            }

        }

        $page_info['site_keywords'] = $meta_array['keywords'];
        $page_info['site_description'] = $meta_array['description'];
        $page_info['meta_array'] = $meta_array;

        # mimvp-site-verification
        preg_match('/<META\s+name="mimvp-site-verification"\s+content="([\w\W]*?)"/si', $output, $matches);
        if (empty($matches[1])) {
            preg_match('/<META\s+content="([\w\W]*?)"\s+name="mimvp-site-verification"/si', $output, $matches);
        }
        if (!empty($matches[1])) {
            $page_info['site_claim_status'] = 1;
        }

        # mimvp-site-verification
        if (strstr($output, 'https://proxy.mimvp.com') != "") {
            $page_info['friend_link_status'] = 1;
        }

        return $page_info;
    }
}
