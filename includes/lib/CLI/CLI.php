<?php
// +----------------------------------------------------------------------
// | Project: 商城
// +----------------------------------------------------------------------
// | Creation: 2022/2/25
// +----------------------------------------------------------------------
// | Filename: CLI.php
// +----------------------------------------------------------------------
// | Explain: cli功能模块
// +----------------------------------------------------------------------
namespace lib\CLI;

use extend\Maintain;

class CLI
{
    //输出互交信息
    public static function index()
    {
        global $argc, $argv;
        if ($argc === 1) {
            //引导输出模式
            self::help();
        } else {
            //指令模式
            $Index = $argv[1];
            if (!$Index) {
                echo '指令有误，请使用命令行打开站点根目录，输入：php cli.php';
                die;
            }
            self::Actions($Index);
        }
    }

    /**
     * @return string
     * 帮助提示信息
     */
    private static function HelpInformation()
    {
        $Content = "===================晴玖系统命令====================
(1)校准全局配置数据               (2)生成数据库安装文件
(3)校准数据库完整性               (4)更新全局数据缓存
(5)完整备份站点数据               (6)恢复指定备份数据
(0)取消
===================================================
请输入命令编号：";
        return $Content;
    }

    /**
     * 帮助面板
     */
    private static function help()
    {
        fwrite(STDOUT, self::HelpInformation());
        $type = (int)fgets(STDIN);
        if (!$type) {
            $type = 0;
        }
        self::Actions($type);
        exit(0);
    }

    /**
     * @param int $Type
     * 执行命令行互交操作
     */
    private static function Actions(int $Type = 0)
    {
        switch ($Type) {
            case 1: //校准全局配置数据
                $res = Maintain::globalConfigureCalibre(2);
                if (!$res) {
                    echo "数据校准失败,请重新尝试！\n";
                } else {
                    echo $res . "\n";
                }
                break;
            case 2: //数据库安装文件生成
                $FliePath = Maintain::databaseGenerate();
                if ($FliePath) {
                    $DirPath = SYSTEM_ROOT . 'extend/log/SQL/';
                    die("数据库安装文件生成成功！文件路径：" . $DirPath . $FliePath . "\n");
                } else {
                    die("数据库安装文件生成失败！\n");
                }
                break;
            case 3: //校准数据库完整性
                $res = Maintain::databaseCalibre();
                if (!$res || $res['code'] !== 1) {
                    echo $res['msg'] ?? "数据校准失败,请重新尝试！";
                    echo "\n";
                } else {
                    self::CalibratingMessageFeedback($res['data']);
                }
                break;
            case 4: //更新全局数据缓存
                if (Maintain::eachCalibre(2)) {
                    echo "全局数据缓存更新成功！\n";
                } else {
                    echo "全局数据缓存更新失败！\n";
                }
                break;
            case 5: //完整备份站点数据
                $FliePath = Maintain::databaseGenerate(3);
                if ($FliePath) {
                    die("整站数据备份成功！\n文件路径：" . SYSTEM_ROOT . 'extend/log/SQL/' . $FliePath . "\n");
                } else {
                    die("整站数据备份失败！\n");
                }
                break;
            case 6: //恢复指定备份数据
                $DirPath = SYSTEM_ROOT . 'extend/log/SQL';
                $FileList = for_dir($DirPath, [], '_backup', 2);

                if (count($FileList) === 0) {
                    die("没有可用备份，请先去备份数据库！\n");
                }

                $Content = "请选择要恢复的备份：\n";
                foreach ($FileList as $key => $File) {
                    $dataSource = file_get_contents($DirPath . '/' . $File);
                    $dataSource = json_decode($dataSource, true);
                    if (!$dataSource) {
                        continue;
                    }
                    $Count = 0;
                    foreach ($dataSource['InstalData'] as $v) {
                        $Count += count($v);
                    }
                    $Content .= "（" . ($key + 1) . "）文件名：{$File}\n     备份时的程序版本号：{$dataSource['ProgramVersion']}\n     备份时间：{$dataSource['CreationTime']}\n     表总数：" . count($dataSource['Structure']) . "个，总数据数：{$Count}条！\n-----------------------------------------------\n";
                    unset($dataSource, $Count);
                }
                $Content .= "（0）取消恢复\n===============================================
请输入备份文件序号：";

                fwrite(STDOUT, $Content);
                $type = (int)fgets(STDIN);
                if (!$type || $type == 0) {
                    die("已取消！\n");
                }
                $res = Maintain::databaseCalibre($FileList[$type - 1]);
                if (!$res || $res['code'] !== 1) {
                    echo $res['msg'] ?? "数据恢复失败,请重新尝试！";
                    echo "\n";
                } else {
                    self::CalibratingMessageFeedback($res['data']);
                }
                break;
            default:
                echo "
已取消！
";
        }
    }

    /**
     * @param $data
     * 数据校准消息反馈
     */
    private static function CalibratingMessageFeedback($data)
    {
        echo "成功校准数据表的数量：{$data['成功校准数据表的数量：']}\n";
        echo "成功校准数据表字段的数量：{$data['成功校准数据表字段的数量：']}\n";
        echo "校准数据表失败的详情：\n" . json_encode($data['校准数据表失败的详情：'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
        echo "成功校准数据表字段失败的详情：\n" . json_encode($data['成功校准数据表字段失败的详情：'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    }
}