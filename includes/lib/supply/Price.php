<?php

namespace lib\supply;


use extend\UserConf;
use Medoo\DB\SQL;

class Price
{
    /**
     * @var array | int
     * 用户等级列表
     */
    public static $RankList = [];

    //价格缓存
    private static $PriceCache = [];

    /**
     * @var array | int
     * 基础加价规则[多用户等级批量加价]
     */
    public static $RuleList = [];


    /**
     * @return bool
     * 通用数据载入
     */
    public static function load()
    {
        if (self::$RankList != -1 && count(self::$RankList) === 0) {
            $DB = SQL::DB();
            $Res = $DB->select('price', '*', ['state' => 1, 'ORDER' => ['sort' => 'ASC']]);
            if (!$Res) {
                self::$RankList = -1;
            } else {
                self::$RankList = $Res;
            }
        }
        if (self::$RuleList != -1 && count(self::$RuleList) === 0) {
            $DB = SQL::DB();
            //需要采用重复载入
            $RuleList = $DB->select('profit_rule', ['id', 'rules'], [
                'state' => 1,
            ]);
            if (!$RuleList) {
                self::$RuleList = -1;
            } else {
                self::$RuleList = $RuleList;
            }
        }
        return true;
    }

    /**
     * @param $Money
     * 根据成本和用户等级计算商品售价
     * 取出全部等级售价列表
     * @param $Profits
     * 商品利润比例
     * @param $Gid
     * 商品ID
     */
    public static function List($Money, $Profits, $Gid = false, $selling = '{}', $SingleRule = -1, $Type = 1)
    {
        self::load();
        $Data = [];
        if (self::$RankList === -1) {
            return $Data;
        }
        foreach (self::$RankList as $key => $value) {
            $Data[] = self::Get($Money, $Profits, ($key + 1), $Gid, $selling, $SingleRule, $Type);
        }
        return $Data;
    }

    /**
     * @param $Money
     * @param $Profits
     * @param int $Level
     * 根据用户等级和成本计算出商品售价！
     * 取出单个
     * @param array $selling
     * 自定义等级规则
     */
    public static function Get($Money, $Profits, $Level = 1, $Gid = false, $selling = '{}', $SingleRule = -1, $Type = 1)
    {
        $NameMd5 = md5($Money . $Profits . $Level . $selling . $SingleRule . $Type);
        if (!empty(self::$PriceCache[$NameMd5])) {
            return self::$PriceCache[$NameMd5];
        }
        self::load();

        $Money = (float)$Money;
        if ($Level === -1 || $Level === false) {
            $Level = 1;
        }

        if (self::$RankList === -1 || count(self::$RankList) === 0) {
            $UserRank = [
                'name' => '站点用户',
                'priceis' => 0,
                'pointsis' => 1000,
                'rule' => -1,
            ];
        } else {
            if ($Level >= count(self::$RankList)) {
                //超出
                $Level = count(self::$RankList);
            }
            $UserRank = self::$RankList[($Level - 1)];
        }

        //计算利润比例
        if ($UserRank['rule'] != -1 && $Profits == 100) {
            if (self::$RuleList && count(self::$RuleList) >= 1) {
                $Profits = self::MatchProfitRatio($Money, $UserRank['rule']);
            }
        }

        if (!empty($selling)) {
            $selling = json_decode($selling, true);
        }

        if (!is_array($selling)) {
            $selling = [];
        }

        /**
         * 价格计算
         */

        if (isset($selling[($Level - 1)]['a']) && $selling[($Level - 1)]['a'] != '') {
            $Price = (float)$selling[($Level - 1)]['a'];
        } else if ($Gid && $SingleRule != -1) {
            //载入商品单配置
            $Price = PriceIncreaseAccordCommonRule($Money, $SingleRule, $Level);
            if (!$Price) {
                $Price = round(($Money + ($Money * ($UserRank['priceis'] / 100)) * ((float)$Profits / 100)), 8);
            }
        } else {
            $Price = round(($Money + ($Money * ($UserRank['priceis'] / 100)) * ((float)$Profits / 100)), 8);
        }

        if (isset($selling[($Level - 1)]['b']) && $selling[($Level - 1)]['b'] != '') {
            $Profits = (float)$selling[($Level - 1)]['b'];
        } else if ($Gid && $SingleRule != -1) {
            //载入商品单配置
            $Profits = PriceIncreaseAccordCommonRule($Money, $SingleRule, $Level, 2);
            if (!$Profits) {
                $Profits = round($Money * $UserRank['pointsis']);
            } else {
                $Profits = round($Profits);
            }
        } else {
            $Profits = round($Money * $UserRank['pointsis']);
        }

        /**
         * 分店加价
         */
        if ($Gid && $Type === 1) {
            $Rise = UserConf::GoodsPrice((int)$Gid);
            $Price += ($Price * ($Rise / 100));
            $Profits += ($Profits * ($Rise / 100));
        }

        //为非免费商品配置保底价格
        if ($Price == 0 && $Profits != 0) {
            $Price = 0.01;
        }
        if ($Price != 0 && $Profits == 0) {
            $Profits = 1;
        }

        self::$PriceCache[$NameMd5] = [
            'price' => $Price,
            'points' => $Profits,
            'name' => $UserRank['name'],
        ];

        return self::$PriceCache[$NameMd5];
    }

    /**
     * @param $freight
     * @param $input
     * @param $price
     * @param $num
     * @return float|int
     * 运费模板,返回的任何数据均已计算过份数售价
     */
    public static function Freight($freight, $input, $price, $num)
    {
        if (($price * $num) >= (float)$freight['threshold']) {
            //减免运费
            return $price * $num;
        }

        $freight_arr = explode('|', $freight['region']);

        $retos = '';
        foreach ($freight_arr as $v) {
            $rets = explode(',', $v);
            if (strpos(json_encode($input, JSON_UNESCAPED_UNICODE), $rets[0]) !== false) {
                $retos = $rets;
            }
        }

        if ($retos !== '') { //运费已自定义地区
            $_SESSION['retos'] = $retos[0] . '运费'; //地区
            $freight['money'] = (float)$retos[1]; //运费
            $freight['exceed'] = (float)$retos[2]; //加价金额！
            $_SESSION['exceed'] = $retos[0] . '地区，份数超出' . $freight['nums'] . '份，则每份需额外加' . round($freight['exceed'], 8) . '元运费';
        } else {
            $_SESSION['retos'] = '通用运费 ';
            $_SESSION['exceed'] = '若购买份数超出' . $freight['nums'] . '份，则每份需额外加' . round($freight['exceed'], 8) . '元运费';
        }

        if ($num > $freight['nums']) { //购买数量超出，加运费
            return round((((float)$price * $num) + (float)$freight['money']) + ((float)$freight['exceed'] * ((float)$num - (float)$freight['nums'])), 8);
        }

        //加正常运费
        return round((((float)$price * $num) + (float)$freight['money']), 8);
    }

    /**
     * @param $moeny
     * @param $rule
     * 解析商品利润比例
     */
    public static function MatchProfitRatio($moeny, $rule = -1)
    {
        $array = false;
        foreach (self::$RuleList as $value) {
            if (!$array && $value['id'] == $rule) {
                if (!$array = json_decode($value['rules'], true)) {
                    //如果ID匹配，无法解析规则，则直接返回100
                    return 100;
                }
            }
        }
        //如果没有匹配成功，则使用默认
        if (!$array) {
            return 100;
        }
        foreach ($array as $value) {
            if ($moeny >= $value['min'] && $moeny <= $value['max']) {
                return $value['profit'] ?? 100;
            }
        }
        //保底利润比例
        return 100;
    }
}
