
<?php
function postValidate($url, $data)
{
    if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('ContentType:application/x-www-form-urlencoded'));  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5*1000);  
        $errno = curl_errno($ch);
        $response = curl_exec($ch);
        curl_close($ch);
        return $errno > 0 ? 'error' : $response;
    } else {
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
                'content' => $data,
                'timeout' => 5*1000
            ),
            'content' => $data
        );
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);
        return $response ? $response : 'error';
    }
}

function getConfig() {
    $config = setting_get('zl_vaptcha');
    $config = $config ? json_decode($config, true) : $config = array(
        "vid" => "",
        "key" => "",
        "type" => "click",
        "color" => '#3c8aff',
        "enable" => array(
            "user_login" => 'on', 
            "user_create" => 'on', 
            "email_code" => 'on', 
            "quick_reply" => 'on', 
            "thread_create" => 'on',
        )
    );
    return $config;
}

function validate($mode) {
    $config = getConfig();
    if (empty($config['enable'][$mode])) return ;
    $vaptchaToken = param('vaptcha_token');
    empty($vaptchaToken) AND message('vaptcha', '请进行人机验证');
    $data = array(
        "id" => $config['vid'],
        "secretkey" => $config['key'],
        "scene" => "",
        "token" => $vaptchaToken,
        "ip" => $_SERVER['REMOTE_ADDR']
    );
    $result = json_decode(postValidate('http://api.vaptcha.com/v2/validate', $data));
    $result->success == 0 AND  message('vaptcha', '请进行人机验证');
}

