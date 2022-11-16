<?php

//{"host":"192.168.92.208","port":81,"path":"\/callback\/ThirdCompanyReceiver\/accountSyncThirdAccount","body":"{\"a\":4444}"}

use function Co\run;

run(function () {
    go(function () {
        $body = '{"aa":"李123"}';
        $url = 'http://192.168.92.208:81/callback/ThirdCompanyReceiver/accountSyncThirdAccount';
        $parse = parse_url($url);

        $host = $parse['host'];
        $ssl = $parse['scheme'] == 'https' ? true : false;
        $port = $parse['scheme'] == 'https' ? 443 : (isset($parse['port']) ? $parse['port'] : 80);
        $path = isset($parse['path']) && !empty($parse['path']) ? $parse['path'] : '/';
        $path = isset($parse['query']) && !empty($parse['query']) ? $path . '?' . $parse['query'] : $path;
        $client = new \Swoole\Coroutine\Http\Client($host, $port, $ssl);
        $client->setHeaders([
            'Host' => $host,
            'Content-Type' => 'application/json;charset=utf-8',
            'Accept' => 'application/json;text/html,application/xhtml+xml,application/xml',
        ]);
        $client->set(['timeout' => 600]);
        $client->post($path, $body);
        $responseBody = $client->body;
        $statusCode = $client->getStatusCode();
        $client->close();
        print_r($responseBody);
    });
});

