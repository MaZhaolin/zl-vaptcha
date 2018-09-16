<?php 
include_once APP_PATH.'plugin/zl_vaptcha/util/validate.php';
function getVaptchaScript($moduleName, $script) {
    $config = getConfig();
    if (empty($config['enable'][$moduleName])) return ;
    $config = setting_get('zl_vaptcha');
    $config = $config ? json_decode($config, true) : $config = array(
        "vid" => "",
        "key" => "",
        "type" => "click",
        "color" => '#3c8aff'
    );
    include_once APP_PATH.'plugin/zl_vaptcha/hook/template.htm';
?>

<script src="https://cdn.vaptcha.com/v2.js"></script>
<script>
    vaptcha({
        vid: '<?php echo $config['vid'] ?>', // 验证单元id
        type: '<?php echo $config['type'] ?>', // 显示类型 点击式
        color: '<?php echo $config['color'] ?>',
        container: '.vaptcha-container' // 按钮容器，可为Element 或者 selector
    }).then(function (obj) {
        obj.renderTokenInput()
        obj.render()// 调用验证实例 vpObj 的 render 方法加载验证按钮
        <?php echo $script ?>
    })
</script>

<?php }?>