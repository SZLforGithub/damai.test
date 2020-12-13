<?php

namespace App\Services;

class CurlService
{
    public function __construct()
    {

    }

    public static function fetchHttpResponse(array $request, &$response)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $request['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($request['header'])) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if (!empty($request['cookieFile'])) {
            curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $request['cookieFile']);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $request['cookieFile']);
        }

        if (!empty($request['cookies'])) {
            curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
            curl_setopt($ch, CURLOPT_COOKIE, $request['cookies']);
        }

        $initHeaders = array(
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36",
            "Connection: Keep-Alive",
            "Cache-Control: no-cache",
            "Accept: */*",
            "Accept-Language: zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7"
        );

        if (!empty($request['headers'])) {
            if (!is_array($request['headers']))
                $request['headers'] = array($request['headers']);

            $initHeaders = array_merge($initHeaders, $request['headers']);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $initHeaders);

        if (!empty($request['post']) && $request['post'])
            curl_setopt($ch, CURLOPT_POST, true);

        if (!empty($request['post_data']))
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request['post_data']);

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);

        curl_close($ch);

        return $info['http_code'];
    }
}