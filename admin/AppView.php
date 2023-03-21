<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2022/9/27
// +----------------------------------------------------------------------
// | Filename: AppView.php
// +----------------------------------------------------------------------
// | Explain: 应用视图
// +----------------------------------------------------------------------
use lib\AppStore\AppList;

$title = $_GET['name'] ?? '应用视图';
include 'header.php';
global $_QET;
if (!isset($_QET['id'])) {
    echo "<h3 style='text-align: center'>请将应用ID提交完整，不要直接访问当前页面！</h3>";
    include 'bottom.php';
    die;
}
$Url = AppList::AppMenuURL($_QET['id'], "Admin");
?>
<iframe id="frame-content" scrolling="auto" frameborder="0"
        style="width: 100%;padding: 0;margin:0;border:none;height: 100vh;" src="<?= $Url ?>"></iframe>
<?php include 'bottom.php'; ?>
<script>
    function calcPageHeight(doc) {
        const cHeight = Math.max(doc.body.clientHeight, doc.documentElement.clientHeight);
        const sHeight = Math.max(doc.body.scrollHeight, doc.documentElement.scrollHeight);
        return Math.max(cHeight, sHeight)
    }

    const ifr = document.getElementById('frame-content');
    ifr.onload = function () {
        const iDoc = ifr.contentDocument || ifr.document || ifr.contentWindow;
        const height = calcPageHeight(iDoc);
        ifr.style.height = height + 'px'
    }
</script>