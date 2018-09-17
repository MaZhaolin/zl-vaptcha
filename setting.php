<?php

!defined('DEBUG') AND exit('Access Denied.');
include_once _include(APP_PATH.'plugin/zl_vaptcha/util/validate.php');

if ($method == 'GET') {
    $config = getConfig();
    include_once _include(APP_PATH.'plugin/zl_vaptcha/setting.htm');
} else {
    $config = json_encode($_POST);
    setting_set('zl_vaptcha', $config);
    echo json_encode(array("code" => 1));
}

