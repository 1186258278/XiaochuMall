<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2022/12/21
// +----------------------------------------------------------------------
// | Filename: HookQueue.php
// +----------------------------------------------------------------------
// | Explain: 钩子队列，延迟执行，防止程序堵塞，采用文件缓存
// +----------------------------------------------------------------------
namespace extend;

use lib\Hook\Hook;

class HookQueue
{

    //钩子缓存列表存储路径
    public static $HookFile = SYSTEM_ROOT . 'lib/Hook/Queue/';

    //需要延迟调用的钩子名称
    public static $HookList = [
        'GoodsShow', 'GoodsHide', 'GoodsConceal', 'GoodsDel',
        'GoodsSet', 'GoodsDetails', 'GoodsAdd', 'GoodsPrecisionSet',
        'GoodsNumberCopyModify', 'GoodsCopy', 'GoodsModifyRemark',
        'GoodsBatchFreightTemEdit', 'GoodsSetInventory', 'GoodsSort',
        'GoodsPriceFluctuate', 'GreatFareIncreaseRule', 'AmendRuleFareIncrease',
        'ClassAdd', 'ClassDel', 'ClassPaySet', 'ClassStateSet', 'ClassSort',
        'ClassShow', 'ClassHide', 'WithdrawNew', 'WithdrawDel', 'WithdrawAudit',
        'KarmaRecharge', 'BalanceRecharge', 'PaySuccess', 'BalanceOver',
        'WorkOrderNew', 'WorkOrderReply', 'WorkOrderEnd', 'UserModifyInform',
        'UserLogout', 'UserLogin', 'UserRegister', 'UserInvite', 'UserLevelUp',
        'UserSignIn', 'PayMoney', 'PayPoints', 'ConfirmReceipt', 'OrderAdd',
        'OrderFinish', 'CartDel', 'CartAdd', 'AppraiseAudit', 'AppraiseNew',
        'FakaLater', 'SalfordDelivery', 'HiddenContentShipment', 'DeliveryPagodaMainframe',
        'HostLogin', 'HostExitLogin', 'HostRenew', 'DeleteHostSpace', 'ActiveHostSpace',
        'ArticleSeek', 'ArticleList', 'RenewUserRight', 'EndUninstallPlugin', 'StartUninstallPlugin',
        'PluginInstalSuccess'
    ];

    /**
     * @param $Name //钩子名称
     * @param $Data //钩子传递的参数
     * 执行添加操作,延迟执行
     */
    public static function execute($Name, $Data = [])
    {
        global $conf;
        if (!strstr($Name, empty($conf['SynchroHookList']) ? '1' : $conf['SynchroHookList']) && !in_array($Name, self::$HookList)) {
            return false;
        }
        $Data['TimeDelay'] = time();
        mkdirs(self::$HookFile);
        $FileName = RandomNumber();
        if (file_put_contents(self::$HookFile . $FileName, json_encode([
            'Name' => $Name,
            'Data' => $Data
        ]))) {
            return true;
        }
        return false;
    }

    /**
     * 执行钩子队列任务
     */
    public static function PerformTask()
    {
        mkdirs(self::$HookFile);
        $List = for_dir(self::$HookFile);
        if (count($List) === 0) {
            return false;
        }
        require_once __DIR__ . '/../lib/Hook/Hook.php';
        //开始执行订单队列
        foreach ($List as $FileName) {
            $PathFile = self::$HookFile . $FileName;
            if (!is_file($PathFile)) {
                continue;
            }
            $Json = file_get_contents($PathFile);
            //读取完成后，删除对应文件，防止重复调用
            @unlink($PathFile);
            if (!$Json) {
                continue;
            }
            if (!$Json = json_decode($Json, true)) {
                continue;
            }
            Hook::execute($Json['Name'], $Json['Data'], 1, 2);
            unset($Json);
        }
        return true;
    }
}