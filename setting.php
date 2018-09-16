<?php

!defined('DEBUG') AND exit('Access Denied.');
function getConfig() {

}

if ($method == 'GET') {
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
    include _include(APP_PATH.'plugin/zl_vaptcha/setting.htm');
} else {
    $config = json_encode($_POST);
    setting_set('zl_vaptcha', $config);
    echo json_encode(array("code" => 1));
}

