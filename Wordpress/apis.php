<?php
include_once 'config.php';
/**
 * Bangumi追番API
 * @author Kengwang
 * @copyright Kengwang
 */


class BGMFL
{
    static function curl($url, $cookie)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //地址
        //curl_setopt ($curl, CURLOPT_COOKIEJAR, $cookiefile);//文件式
        //curl_setopt ($curl, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }
}
class bilibili
{
    private static function getUserInfo($arg)
    {
        //之后记得换成WordPress的函数啊,天啊
        $arr = array(
            'uid' => '341151171', //用户ID
            'cookie' => 'SESSDATA=*******打码233****' //cookie,假如说没有公开就要填写,只需要SESSDATA
        );
        if (isset($arr[$arg])) return $arr[$arg];
        else return '';
    }

    public static function getFollowingListRaw()
    {
        /**
         * 关于链接的注释
         * 首先说说已知的参数
         * type :          追逐类型 1为追番 2为追剧 [必须]
         * follow_status : 番剧类别 0为全部 1为想看 2为在看 3为看过
         * ps :            每一页的个数 相当于SQL的Limit
         * pn :            页码,承接上一个参数
         * vmid :          用户ID [必须]
         * 
         * 还有什么参数后期补充哦
         */
        $back = Functions::curl("https://api.bilibili.com/x/space/bangumi/follow/list?type=1&follow_status=0&vmid=" . self::getUserInfo('uid'), self::getUserinfo('cookie'));

        $array = json_decode($back, true);
        return $array;
    }

    public static function getFollowingList()
    {
        return self::getFollowingListRaw()['data']['list'];
    }

    private static function getSubstr($str, $leftStr, $rightStr)
    {
        $left = strpos($str, $leftStr);
        //echo '左边:'.$left;
        $right = strpos($str, $rightStr, $left);
        //echo '<br>右边:'.$right;
        if ($left < 0 or $right < $left) return '';
        return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
    }

    public static function getFormatList($data = null)
    {
        /**
         * 按照老夫追了那么多番剧,看了那么多情况,可以将它归纳一下
         * 
         * name    : 番剧名称 [Done]
         * status  : 番剧状态 0为正在播 1为将开 2为完结 [Done]
         * followst: 追番状态(自动判断) 0为未看,1为在看,2为看完 [Done]
         * basket  : 在B站定的状态,0为想看 1为在看 2为看完 [Done]
         * all     : 总集数 未开为0 [Done]
         * watched : 已观看集数 PV为0 [Done]
         * progress: 特色功能,追番进度 n% [Done]
         * img     : 图片 [Done]
         * coin    : 硬币 [Done]
         * score   : 分数 未开/未评分番剧是0.0 [Done]
         * new     : 最新集(array)={title:名字 ep:集数 finish:是否完结撒花} [Waiting]
         * 
         */
        if ($data == null) {
            $data = getFollowingList();
        }
        $ret = array();
        foreach ($data as $bangumi) {
            $temp['name'] = $bangumi['title']; // 名称
            //番剧状态
            if ($bangumi['is_finish']) {
                $temp['status'] = 2;
            } elseif (!$bangumi['is_started']) {
                $temp['status'] = 1;
            } else {
                $temp['status'] = 0;
            }

            //获取总集数
            if ($bangumi['is_finish']) {
                $total = $bangumi['total_count'];//total_count是预计总集数
            } elseif (!strpos($bangumi['new_ep']['index_show'], '即将开播')) {
                $total = 0;
            } else {
                $total = $bangumi['new_ep']['title'];
            }
            $temp['all'] = $total;

            //获取当前看到的集数
            $ep = 0;
            if (strpos($bangumi['progress'], 'PV') || strpos($bangumi['progress'], '将开') || $bangumi['is_started']) { //没有开始
                $ep = 0;
            } elseif ($bangumi['is_finish']) {
                $ep = $total;
            } elseif (!$bangumi['is_start']) {
                $ep = 0;
            } else {
                $ep = self::getSubstr($bangumi['progress'], '第', '话'); //匹配左右取中间数字
            }

            //追番状态 - Auto
            if (!$bangumi['is_started'] || $ep = 0) {
                $temp['followst'] = 0;
            } elseif ($ep == $total) {
                $temp['followst'] = 2;
            } else {
                $temp['followst'] = 1;
            }

            //追番状态 - Bilibili
            $temp['basket'] = $bangumi['follow_status'] - 1;

            //图片 - 原图 (非方形小头像)
            $temp['img'] = $bangumi['cover'];

            //硬币
            $temp['coin'] = $bangumi['stat']['coin'];

            //分数
            $temp['score'] = $bangumi['rating']['score'];

            //进度
            $percent= floor($ep*100/$total);
            $temp['progress']=$percent;

            $ret[] = $temp;
            //循环尾 看清楚
        }
        return $ret;
    }
}
