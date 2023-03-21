<?php
/**
 * Author：晴玖天
 * Creation：2020/4/13 11:41
 * Filename：SqlBackups.php
 * 数据库备份操作类
 */

namespace extend;


use config;
use Curl\Curl;
use Medoo\DB\SQL;

class SqlBackups
{
    /**
     * @param $name 备份文件名称
     * 上传备份文件到云端！
     */
    public static function SqlBackupsUpdate($name)
    {
        if (!file_exists(ROOT . "includes/extend/log/Backup/" . $name)) dies(-1, '备份文件不存在！');
        $Data = Curl::curl(false, ['flie_url' => $name, 'act' => 'update'], true, '/SQLmanaged/index', 2);
        dier($Data);
    }

    /**
     * 从服务端下载备份文件到本地！
     */
    public static function SqlBackupsDownloadLocal()
    {
        global $accredit;
        $Data = Curl::curl(false, ['act' => 'download'], true, '/SQLmanaged/index', 2);
        if ($Data['code'] == -1) dier($Data);
        if ($Data == '') dies(-1, '备份数据获取失败！');
        $Url = get_curl(xiaochu_de($Data['Flie'], $accredit['token']));
        $filename = 'cloud_' . $Data['date_time'] . '_' . md5($accredit['token']) . ".sql";
        $path = SYSTEM_ROOT . 'extend/log/Backup/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (count(explode(';SPLITXCY', $Url)) == 0) dies(1, '云端备份文件获取失败！<br>请手动下载：<a href=' . xiaochu_de($Data['Flie'], $accredit['token']) . ' target=_blank >下载</a>');
        $myfile = fopen($path . $filename, "w") or dies(1, '云端备份文件获取失败！<br>请手动下载：<a href=' . xiaochu_de($Data['Flie'], $accredit['token']) . ' target=_blank >下载</a>');
        $encode = mb_detect_encoding($Url, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $Url = mb_convert_encoding($Url, 'UTF-8', $encode);
        fwrite($myfile, $Url);
        fclose($myfile);
        dies(1, '云端文件下载成功,文件名称：<br>' . $filename . '<br>此备份上传时间为<font color=red>' . $Data['date'] . '</font>');
    }

    /**
     * 执行Mysql数据库备份操作！
     */
    public static function MysqlBackups()
    {
        global $dbconfig, $accredit, $date;
        header('Content-Type:text/html;charset=utf8');
        ini_set("max_execution_time", "0");
        ini_set('memory_limit', '1024M');
        date_default_timezone_set("PRC");
        header("Content-Type:text/html;charset=utf-8");
        $con = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
        mysqli_select_db($con, $dbconfig['dbname']);
        $mysql = "set charset utf8;;SPLITXCY";
        mysqli_query($con, "SET NAMES 'UTF8'");
        $q1 = mysqli_query($con, "show tables");
        $Count = 0;
        $Count_TAB = 0;
        while ($t = mysqli_fetch_array($q1)) {
            $table = $t[0];
            $q2 = mysqli_query($con, "show create table `$table`");
            $sql = mysqli_fetch_array($q2);
            if (strstr($sql['Table'], 'cache')) continue;
            $mysql .= 'DROP TABLE IF EXISTS ' . $sql['Table'] . ";;SPLITXCY";
            $mysql .= $sql['Create Table'] . ";;SPLITXCY";
            $q3 = mysqli_query($con, "select * from `$table`");
            ++$Count_TAB;
            while ($data = mysqli_fetch_assoc($q3)) {
                $keys = array_keys($data);
                $keys = array_map('addslashes', $keys);
                $keys = join('`,`', $keys);
                $keys = "`" . $keys . "`";
                $vals = array_values($data);
                $vals = array_map('addslashes', $vals);
                $vals = join("','", $vals);
                $vals = "'" . $vals . "'";
                $mysql .= "INSERT INTO `$table`($keys) VALUES($vals);;SPLITXCY";
                unset($data);
                ++$Count;
            }
            unset($t);
        }
        mysqli_close($con);
        $filename = date('YmdHis') . '_' . md5($accredit['token']) . ".sql";
        $path = SYSTEM_ROOT . 'extend/log/Backup/';
        mkdirs($path);
        $file_name = $path . $filename;
        $fp = fopen($file_name, 'w');
        fputs($fp, $mysql);
        fclose($fp);
        $fp = fopen($file_name, "r");
        $file_size = filesize($file_name);
        fread($fp, $file_size);
        fclose($fp);
        @header('Content-Type: application/json; charset=UTF-8');
        return [
            'code' => 1,
            'msg' => '数据备份成功<br>本次成功备份了' . $Count_TAB . '个数据表！<br>共有' . $Count . '条数据！<br>备份完成时间：' . $date
        ];
    }

    /**
     * @param $name
     * @param $date
     * 可执行文件下载
     */
    public static function SqlBackupsDownload($name, $date)
    {
        $path = ROOT . 'includes/extend/log/Backup/';
        mkdirs($path);
        $date = str_replace(["-", " ", ":"], "", $date);
        $file_name = $path . $date . '.sql';
        if (file_exists($file_name)) dier([
            'code' => 1,
            'msg' => '下载地址已经分配好,若未自动下载,请点击确定手动下载！',
            'flie' => "/includes/extend/log/Backup/" . $date . '.sql'
        ]);

        if (!file_exists(ROOT . "includes/extend/log/Backup/" . $name)) dies(-1, '备份文件不存在！');
        $Content = file_get_contents(ROOT . "includes/extend/log/Backup/" . $name);
        $Content = str_replace([";SPLITXCY"], "\r\n", $Content);
        $myfile = fopen($file_name, "w") or dies(-1, '可执行文件创建失败');
        fwrite($myfile, $Content);
        fclose($myfile);
        dier([
            'code' => 1,
            'msg' => '下载地址已经分配好,若未自动下载,请点击确定手动下载！',
            'flie' => "/includes/extend/log/Backup/" . $date . '.sql'
        ]);
    }

    public static function SqlBackupsList($_QET)
    {
        $page = ((int)$_GET['page'] - 1) * (int)$_GET['limit'];
        $limit = (int)$_GET['limit'] + $page;
        $dir = ROOT . "includes/extend/log/Backup/";
        mkdirs($dir);
        $file = scandir($dir);
        $array = [];
        $list = 0;
        foreach (array_reverse($file) as $value) {
            if ($value == '.' || $value == '..' || !strstr($value, '_')) continue;
            ++$list;
            if ($list >= $page && $list <= $limit) {
                $arr = explode('_', $value);
                $CONTENT = file_get_contents(ROOT . "includes/extend/log/Backup/" . $value);
                $Date = filemtime(ROOT . "includes/extend/log/Backup/" . $value);
                $array[] = ['date' => date("Y-m-d H:i:s", $Date), 'filename' => $value, 'count' => count(explode(';SPLITXCY', $CONTENT)) . '条'];
            } else continue;
        }
        dier([
            'code' => 0,
            'msg' => '列表获取成功',
            'count' => count($file) - 2,
            'data' => $array
        ]);
    }

    /**
     * @param $name
     * 删除备份文件
     */
    public static function SqlBackupsDel($name)
    {
        if (!file_exists(ROOT . "includes/extend/log/Backup/" . $name)) dies(-1, '备份文件不存在！');
        if (unlink(ROOT . "includes/extend/log/Backup/" . $name)) {
            dies(1, '删除成功');
        } else dies(-1, '删除失败！');
    }

    /**
     * @param string $name 恢复备份数据
     * 每100条数据100条数据的恢复！
     */
    public static function SqlBackupsRecovery($name = '', $page = 1, $LIMIT = 100)
    {
        if (!isset($_SESSION[$name])) {
            if (!file_exists(ROOT . "includes/extend/log/Backup/" . $name)) dies(-1, '备份文件不存在！');
            $Content = file_get_contents(ROOT . "includes/extend/log/Backup/" . $name);

            $encode = mb_detect_encoding($Content, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
            $Content = mb_convert_encoding($Content, 'UTF-8', $encode);

            $SQL = explode(';SPLITXCY', $Content);

            $_SESSION[$name] = $SQL;

        } else {
            $SQL = $_SESSION[$name];
        }
        $DB = SQL::DB();
        $SQL = array_slice($SQL, (($page - 1) * $LIMIT), $LIMIT);
        if (count($SQL) <= 0) {
            config::unset_cache();
            dies(2, '执行完毕！');
        }
        $SQLARR = [];
        foreach ($SQL as $key => $value) {
            if ($value == '') continue;
            /**
             * 输出SQL数据！
             */
            $re = $DB->query(trim($value));
            $SQLARR[] = ['sql' => $value, 'type' => ($re ? 1 : $DB->error())];
            unset($re);
        }
        dier(['code' => 1, 'msg' => '成功', 'sql' => $SQLARR]);
    }
}