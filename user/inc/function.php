<?php
function getIPLoc_QQ1($queryIP)
{
    $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1=' . $queryIP;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_ENCODING, 'gb2312');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
    $result = curl_exec($ch);
    $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码 
    curl_close($ch);
    preg_match("@<span>(.*)</span></p>@iU", $result, $ipArray);
    $loc = $ipArray[1];
    return $loc;
}

function getIPLoc_QQ($queryIP)
{
    $url = 'http://wap.ip138.com/ip.asp?ip=' . $queryIP;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_ENCODING, 'utf-8');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
    $result = curl_exec($ch);
    curl_close($ch);
    preg_match("@<br/><b>(.*)</b>@iU", $result, $ipArray);
    $loc = $ipArray[1];
    $loc = mb_ereg_replace("查询结果：", "", $loc);
    return $loc;
}

function get_ip()
{
    $ip = false;
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) {
            array_unshift($ips, $ip);
            $ip = FALSE;
        }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
