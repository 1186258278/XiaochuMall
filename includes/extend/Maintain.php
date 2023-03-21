<?php
//代码注释是什么？我都快看不懂自己代码了

namespace extend;

use Curl\Curl;
use ins\install;
use lib\supply\StringCargo;
use Medoo\DB\SQL;
use ZipArchive;

class Maintain
{
    public static $Calibre = [
        'config'
    ];

    public static $DataTable = [
        'price', 'class', 'profit_rule'
    ];

    public static function run()
    {
        $Res = self::databaseCalibre();
        self::globalConfigureCalibre(2);
        self::eachCalibre(2);
        return $Res;
    }

    public static function globalConfigureCalibre($type = 1)
    {
        include_once SYSTEM_ROOT . "fun.test.php";
        $ConfigList = install::ConfigurationTable();
        $DB = SQL::DB();
        $Count = $DB->count('config');
        if ($Count >= count($ConfigList) && $type === 1) {
            return "无需校准全局配置数据,请刷新页面";
        }
        $List = $DB->select('config', '*');
        $ListConfig = [];
        foreach ($List as $value) {
            if (empty($value['C'])) {
                continue;
            }
            $ListConfig[$value['K']] = $value['C'];
        }
        $success = 0;
        foreach ($ConfigList as $key => $value) {
            if (!isset($ListConfig[$key])) {
                $Ex = explode('|', $value);
                if ($DB->insert('config', [
                    'K' => $key,
                    'V' => $Ex[0],
                    'C' => $Ex[1],
                ])) {
                    ++$success;
                }
                unset($Ex);
            }
        }
        if ($success > 0) {
            self::clearGlobalEach();
        }
        unset($ListConfig, $List, $ConfigList);
        return '本次新增了' . $success . '条全局参数！';
    }


    public static function Caching($name = 'config')
    {
        $DB = SQL::DB();
        $Get = $DB->get('cache', ['V'], [
            'K' => $name
        ]);
        if (!$Get) {
            $dbList = SQL::DBS()->query("show tables");
            $i = 0;
            while (mysqli_fetch_assoc($dbList)) ++$i;
            if ($i === 0) {
                @unlink(ROOT . 'install/install.lock');
                return [];
            }
            self::eachCalibre(2, $name);
            return self::Caching($name);
        }
        $data = json_decode($Get['V'], true);
        if (!$data && $name == 'config') {
            if (!self::eachCalibre(2, $name)) {
                return [];
            }
            return self::Caching($name);
        }
        return $data;
    }

    public static function databaseCalibre($FileName = false, $type = 1, $install = false)
    {
        global $dbconfig;
        $dbconfig['prefix'] = 'sky_';
        $DirPath = SYSTEM_ROOT . '/extend/log/SQL';
        $FileList = for_dir($DirPath, [], '_backup');
        if (count($FileList) === 0) {
            return false;
        }
        sort($FileList);
        $FileList = array_reverse($FileList);
        $List = [];
        foreach ($FileList as $value) {
            if (strlen($value) !== 14) {
                continue;
            }
            $List[] = $value;
        }

        if ($FileName) {
            $List[0] = $FileName;
        }
        $dataSource = file_get_contents($DirPath . '/' . $List[0]);
        $dataSource = json_decode($dataSource, true);
        if (!$dataSource) {
            return [
                'code' => -1,
                'msg' => '无法读取数据源[数据库校准文件]！，请检查数据源内容是否正确！'
            ];
        }
        unset($FileList, $List);
        $latestDataStructure = self::databaseGenerate(2);
        $DB = SQL::DB();
        $numberSuccessCalibre_1 = 0;
        $numberSuccessCalibre_2 = 0;
        $numberCalibreFailure_1 = [];
        $numberCalibreFailure_2 = [];
        foreach ($dataSource['Structure'] as $key => $value) {
            $key = str_replace($dbconfig['prefix'], '', $key);
            $table = $dbconfig['prefix'] . $key;
            //配置表前缀
            $value[0][0] = str_replace("`{$key}`", "`$table`", $value[0][0]);

            $StandardSQL = $latestDataStructure[$key] ?? false;
            if ($type === 2) {
                $DB->query("DROP TABLE IF EXISTS `{$table}`;");
            }

            if (!$StandardSQL || $type === 2) {
                if ($DB->query(implode("\n", $value[0]))) {
                    ++$numberSuccessCalibre_1;
                } else $numberCalibreFailure_1[$table] = $DB->error();
            } else {
                $totalNumberTableField = count($value[0]);
                $totalNumberCurrentTableField = count($latestDataStructure[$key][0]);
                $calibreTableFieldName = $value[0][$totalNumberTableField - 2];
                $currentTableFieldName = $latestDataStructure[$key][0][$totalNumberCurrentTableField - 2];
                if ($calibreTableFieldName !== $currentTableFieldName) {
                    if ($DB->query(trim("ALTER TABLE `{$table}` DROP PRIMARY KEY,ADD {$calibreTableFieldName}"))) {
                        ++$numberSuccessCalibre_1;
                    } else {
                        $numberCalibreFailure_1[$table . '_key'] = $DB->error();
                    }
                }
                $After = false;
                $statementExecute = $latestDataStructure[$key][1];
                $Kie = $statementExecute;

                $SortList = [];
                foreach ($value[1] as $k => $v) {
                    $SortList[$k] = ($After ? " AFTER {$After} ;" : " FIRST ;");
                    if (!isset($statementExecute[$k])) {
                        $statementExecute2 = "ALTER TABLE `{$table}` ADD COLUMN " . rtrim($v, ",") . ($After ? " AFTER {$After} ;" : " FIRST ;");
                    } else if ($v !== $statementExecute[$k]) {
                        $statementExecute2 = "ALTER TABLE `{$table}` MODIFY COLUMN " . rtrim($v, ",");
                    } else {
                        $statementExecute2 = false;
                    }

                    if ($statementExecute2 && !isset($statementExecute[$k]) || $v !== $statementExecute[$k]) {
                        if ($DB->query($statementExecute2)) {
                            ++$numberSuccessCalibre_2;
                        } else {
                            $numberCalibreFailure_2[$table . '_' . $k] = $DB->error();
                        }
                        unset($statementExecute2);
                    }
                    $After = $k;
                    unset($Kie[$k]);
                }
                unset($After, $statementExecute);
                if (count($Kie) !== 0) {
                    $statementExecute3 = "ALTER TABLE `{$table}` ";
                    foreach ($Kie as $k => $v) {
                        $statementExecute3 .= "DROP COLUMN {$k} ,";
                    }
                    $statementExecute3 = rtrim($statementExecute3, ",") . ";";
                    if ($DB->query($statementExecute3)) {
                        ++$numberSuccessCalibre_1;
                    } else {
                        $numberCalibreFailure_1[$table . '_delete'] = $DB->error();
                    }
                    unset($statementExecute3, $Kie);
                }
                $statementExecute4 = "ALTER TABLE `{$table}` ";
                foreach ($SortList as $k => $v) {
                    $statementExecute4 .= "MODIFY COLUMN " . rtrim($value[1][$k], ",") . rtrim($v, ";") . ",";
                }
                $statementExecute4 = rtrim($statementExecute4, ",");
                if (!$DB->query($statementExecute4)) {
                    $numberCalibreFailure_1[$table . '_sort'] = $DB->error();
                }
                $classType = $latestDataStructure[$key][0][$totalNumberCurrentTableField - 1];
                if (!strstr($classType, 'InnoDB') || !strstr($classType, 'utf8mb4;') || !strstr($classType, 'utf8mb4_general_ci')) {
                    if (!$DB->query("ALTER TABLE `$table` ENGINE = InnoDB, CHARACTER SET = utf8mb4, COLLATE = utf8mb4_general_ci;")) {
                        $numberCalibreFailure_1[$table . '_engine'] = $DB->error();
                    }
                }
                unset($calibreTableFieldName, $currentTableFieldName, $totalNumberTableField, $totalNumberCurrentTableField, $SortList, $statementExecute4, $classType);
            }

            if ($install && $type === 2) {
                switch ($table) {
                    case 'sky_user':
                        $StartInstall = 1000;
                        break;
                    default:
                        $StartInstall = 1;
                        break;
                }
                @$DB->query("ALTER TABLE `{$table}` AUTO_INCREMENT = {$StartInstall};");
            }

            if (isset($dataSource['InstalData'][$key])) {
                if ($DB->count($key, []) === 0 || $FileName) {
                    if ($FileName) {
                        $DB->delete($key, []);
                    }
                    foreach ($dataSource['InstalData'][$key] as $v) {
                        if ($DB->query(str_replace("INSERT INTO `{$key}`", "INSERT INTO `$table`", $v))) {
                            ++$numberSuccessCalibre_1;
                        } else {
                            $numberCalibreFailure_1[$table . '_import'] = $DB->error();
                        }
                    }
                }
            }
        }

        self::MysqlRepair();
        return [
            'code' => 1,
            'msg' => '校准成功',
            'data' => [
                '成功校准数据表的数量：' => $numberSuccessCalibre_1,
                '成功校准数据表字段的数量：' => $numberSuccessCalibre_2,
                '校准数据表失败的详情：' => $numberCalibreFailure_1,
                '成功校准数据表字段失败的详情：' => $numberCalibreFailure_2,
            ]
        ];
    }

    public static function databaseGenerate($type = 1)
    {
        global $dbconfig, $accredit, $date;
        $dbconfig['prefix'] = 'sky_';
        $DB = SQL::DBS();
        $dbList = $DB->query("show tables");
        $Structure = [];
        while ($table = mysqli_fetch_assoc($dbList)) {
            if (empty($table['Tables_in_' . $dbconfig['dbname']])) continue;
            $table_name = str_replace($dbconfig['prefix'], '', $table['Tables_in_' . $dbconfig['dbname']]);
            $Structure[$table_name] = self::databaseTable($table['Tables_in_' . $dbconfig['dbname']], $table_name);
        }
        if ($type === 2) {
            return $Structure;
        }
        $DataFileName = date('YmdHis');
        $InstalData = [];
        if ($type === 3) {
            self::$DataTable = array_keys($Structure);
            $DataFileName .= "_backup";
        }
        foreach (self::$DataTable as $value) {
            $InstalData[$value] = self::ExportTableData($value, $Structure[$value]);
        }
        $Path = SYSTEM_ROOT . 'extend/log/SQL/';
        mkdirs($Path);
        if (file_put_contents($Path . $DataFileName, json_encode([
            'ProgramVersion' => $accredit['versions'],
            'CreationTime' => $date,
            'Structure' => $Structure,
            'InstalData' => $InstalData
        ], JSON_PRETTY_PRINT))) {
            return $DataFileName;
        }
        return false;
    }

    public static function ExportTableData($table, $MeterHead)
    {
        $DB = SQL::DB();
        $Count = $DB->count($table);
        if ($Count === 0) {
            return [];
        }
        $Header = array_keys($MeterHead[1]);
        $Select = $DB->select($table, '*', []);
        $SQL = "INSERT INTO `{$table}` (";
        $FieldCount = count($Header) - 1;
        foreach ($Header as $k => $Field) {
            if ($k === $FieldCount) {
                $SQL .= " {$Field}";
            } else {
                $SQL .= " {$Field},";
            }
        }
        $SQL .= ") VALUES (";
        $DataList = [];
        foreach ($Select as $value) {
            $SQL2 = $SQL;
            $value = array_map('addslashes', $value);
            $i = 0;
            foreach ($value as $k => $v) {
                $_SQL = $MeterHead[1]["`{$k}`"];
                if (strstr($_SQL, 'DEFAULT NULL') && empty($v)) {
                    $v = " NULL";
                } else if ((strstr($_SQL, 'int(') || strstr($_SQL, 'decimal(')) && !empty($v)) {
                    $v = "$v";
                } else {
                    $v = " '{$v}'";
                }
                if ($i === $FieldCount) {
                    $SQL2 .= "{$v}";
                } else {
                    $SQL2 .= "{$v},";
                }
                ++$i;
            }
            $SQL2 .= ");";
            $DataList[] = $SQL2;
            unset($_SQL);
        }
        unset($FieldCount, $SQL2, $Select);
        return $DataList;
    }

    public static function databaseTable($table, $table_name)
    {
        $DB = SQL::DBS();
        $Res = $DB->query("show create table `$table`");
        $SQL = mysqli_fetch_assoc($Res)['Create Table'];
        $SQL = explode("\n", $SQL);
        $SQL[0] = str_replace($table, $table_name, $SQL[0]);

        $Ans = [];
        foreach ($SQL as $value) {
            if (strstr($value, 'CREATE TABLE') || strstr($value, 'PRIMARY KEY') || strstr($value, ') ENGINE=')) {
                continue;
            }
            $Ex = explode(" ", $value);
            $Ans[$Ex[2]] = $value;
            unset($Ex);
        }
        return [
            $SQL,
            $Ans,
        ];
    }

    public static function eachCalibre($type = 1, $name = false)
    {
        if ($name) {
            $ListCal = self::$Calibre;
            self::$Calibre = [$name];

            if ($name === 'config') {
                self::globalConfigureCalibre();
            }
        }
        $DB = SQL::DB();
        $List = $DB->select('cache', '*');
        if (!$List) {
            $List = [];
        }
        $CalibreList = [];
        foreach ($List as $value) {
            $CalibreList[$value['K']] = $value['V'];
        }
        foreach (self::$Calibre as $value) {
            if (empty($CalibreList[$value]) || $type === 2) {

                $SetList = $DB->select($value, '*');
                if ($value === 'config') {

                    $SetList = self::ConfigConversion($SetList);
                }
                if (!isset($CalibreList[$value])) {
                    $DB->insert('cache', [
                        'K' => $value,
                        'V[JSON]' => $SetList,
                    ]);
                } else {
                    $DB->update('cache', [
                        'V[JSON]' => $SetList,
                    ], [
                        'K' => $value,
                    ]);
                }
                unset($SetList);
            }
        }
        if ($name) {
            self::$Calibre = $ListCal;
        }
        unset($CalibreList, $List);
        return true;
    }

    public static function ConfigConversion($data)
    {
        $list = [];
        foreach ($data as $value) {
            $list[$value['K']] = $value['V'];
        }
        return $list;
    }

    public static function clearGlobalEach($name = false)
    {
        $DB = SQL::DB();
        if ($name) {
            $res = $DB->delete('cache', [
                'K' => $name
            ]);
        } else {
            $res = $DB->delete('cache', []);
        }
        if ($res) {
            return true;
        }
        return false;
    }

    public static function MysqlRepair()
    {
        $Mysql = [
            'pay' => [
                'endtime',
            ],
            'queue' => [
                'endtime',
            ],
            'order' => [
                'finishtime',
            ],
            'token' => [
                'endtime',
            ],
            'withdrawal' => [
                'endtime',
            ],
            'login' => [
                'finish_time',
            ],
            'invite' => [
                'draw_time',
            ],
            'goods' => [
                'date',
            ],
            'coupon' => [
                'endtime',
                'gettime',
                'expirydate'
            ],
        ];
        $DBS = SQL::DB();
        $I = 0;
        foreach ($Mysql as $k => $v) {
            foreach ($v as $vs) {
                $Res = $DBS->update($k, [
                    $vs => NULL
                ], [
                    $vs => '0000-00-00 00:00:00'
                ]);
                if ($Res) ++$I;
            }
        }
        return '本次共成功调整' . $I . '个表';
    }

    /**
     * 程序文件校准模块
     */
    public static function FileCalibreModel()
    {
        global $accredit;
        $Json = Curl::Get('api/FileCalibre/index', [
            'act' => 'GetPackage',
            'version' => $accredit['versions'],
        ]);
        if (!$Json = json_decode($Json, true)) {
            dies(-1, '当前版本的安装包下载地址获取失败，无法完成文件校准！');
        }
        if ($Json['code'] != 1) {
            dies(-1, $Json['msg']);
        }
        $ZipFile = ROOT . $accredit['versions'] . '.zip';
        if (!is_file($ZipFile)) {
            if (!copy($Json['download'], $ZipFile)) {
                dies(-1, '程序完整包下载失败，请手动下载完整包，并且存放到站点根目录，再开始尝试校验！');
            }
        }
        $zip = new ZipArchive;
        if ($zip->open($ZipFile) !== TRUE) {
            dies(-1, $accredit['versions'] . '安装包打开失败，无法完成解压！');
        }
        $Path = ROOT . 'Temps/';
        DelDir($Path);
        mkdirs($Path);
        $zip->extractTo($Path);
        $zip->close();
        $array = [
            'assets/log/',
            'assets/favicon.ico',
            'assets/img/logo.png',
            'includes/extend/log/',
            'includes/deploy.php',
            'includes/lib/soft/',
            'includes/lib/rule/',
            'includes/lib/AppStore/confs.json',
            'includes/lib/AppStore/Hover.json',
            'includes/lib/AppStore/pay.json',
            'includes/lib/Guard/guard.log',
            'includes/lib/Hook/Hook.json',
            'includes/lib/SourceShield/SourceShield.log',
        ];
        foreach ($array as $value) {
            if (is_dir($Path . $value)) {
                DelDir($Path . $value);
            } else if (is_file($Path . $value)) {
                unlink($Path . $value);
            }
        }
        DirCopy($Path, ROOT);
        DelDir($Path);
        dies(1, '程序文件校准成功！');
    }

    /**
     * 同步货源对接文件
     */
    public static function SynchroSourceDockFile()
    {
        $Json = Curl::Get('api/FileCalibre/index', [
            'act' => 'DockFileList'
        ]);
        if (!$Json = json_decode($Json, true)) {
            dies(-1, '校准文件数据获取失败，请重新尝试或联系官方客服处理！');
        }
        if ($Json['code'] != 1) {
            dies(-1, $Json['msg'] ?? '数据获取失败！');
        }
        $Path = SYSTEM_ROOT . 'lib/supply/';
        $success = 0;
        foreach ($Json['data'] as $key => $value) {
            if (in_array($key . '.php', StringCargo::$exclude) && $key != 'Api') {
                continue;
            }
            if (file_put_contents($Path . $key . '.php', $value)) {
                ++$success;
            }
        }
        $File = for_dir(SYSTEM_ROOT . 'lib/supply', StringCargo::$exclude);
        $CacheName = md5(json_encode($File));
        mkdirs(SYSTEM_ROOT . "extend/log/Supply/");
        $ConfFile = SYSTEM_ROOT . 'extend/log/Supply/Docking_' . $CacheName . '.json';
        @unlink($ConfFile);
        dies(1, '文件校准完成，共有' . count($Json['data']) . '个货源对接文件，校准成功了' . $success . '个！');
    }
}