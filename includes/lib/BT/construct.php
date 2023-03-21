<?php

/**
 * Author：晴天 QQ：1186258278
 * Creation：2020/4/12 14:07
 * Filename：construct.php
 * 开通服务器部署程序！
 */

namespace BT;

use CURLFile;
use Medoo\DB\SQL;

class Construct extends Config
{
    /**
     * @param $ID
     * @param false $Flie
     * @return array
     * 获取主机和服务器数据
     * 初始化数据
     */
    public static function DataV($ID, $Flie = false)
    {
        if (strpos($Flie, '..') !== false) {
            dies(-1, '不安全的路径');
        }
        $DB = SQL::DB();
        $MainframeData = $DB->get('mainframe', '*', [
            'id' => $ID,
            'state' => 1,
        ]);
        if (!$MainframeData) dies(-1, '主机空间不存在,或已失效！');
        if ($MainframeData['type'] != 1) dies(-1, '请先激活主机空间！');
        $ServerData = $DB->get('server', '*', ['id' => $MainframeData['server'], 'state' => 1]);
        if (!$ServerData) dies(-1, '节点不存在,或已经关闭,请咨询管理员！');
        self::Conf($ServerData);
        if (!empty($Flie)) {
            $flie = '/' . implode('/', explode('.', $Flie));
        } else {
            $flie = '';
        }
        return [
            'MainframeData' => $MainframeData,
            'flie' => $flie,
            'ServerData' => $ServerData,
        ];
    }

    /**
     * @param $id
     * @param string $error
     * @return bool|\PDOStatement
     * 写入服务器或主机的异常错误数据
     */
    public static function WriteException($id, $error = '数据异常', $type = 1)
    {
        $DB = SQL::DB();
        return $DB->update(($type === 1 ? 'server' : 'mainframe'), [
            ($type === 1 ? 'error' : 'return') => $error,
        ], [
            'id' => $id,
        ]);
    }

    /**
     * @param int $page 页数
     * @param int $LIMIT 每页数量
     * @param int $type
     * @param null $name
     * @return mixed
     * 取出网站列表
     */
    public static function GetList($page = 1, $LIMIT = 15, $type = 1, $name = null)
    {
        //拼接URL地址
        $url = self::$BT_PANEL . '/data?action=getData&table=sites';
        $data = [
            'p' => $page,
            'limit' => $LIMIT,
            'type' => $type,
            'order' => 'id desc',
            'search' => $name,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }


    /**
     * @return array|mixed
     * 创建网站！
     */
    public static function Getaddsite($Data = [])
    {
        //拼接URL地址
        $url = self::$BT_PANEL . '/site?action=AddSite';
        $data = [
            'webname' => '{"domain":"' . $Data['identification'] . '.com","domainlist":[],"count":0}', //主目录
            'path' => self::$PATH . $Data['identification'], //根目录
            'type_id' => self::$TYPE,
            'type' => 'PHP',
            'version' => 70, //PHP版本
            'port' => 88,
            'ps' => $Data['identification'], //备注
            'ftp' => 'false',
            'sql' => 'true',
            'codeing' => 'utf8',
            'datauser' => 'sql_usna_' . $Data['identification'],
            'datapassword' => 'sql_pass_' . $Data['identification'],
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    /**
     * 网站到期时间设置
     */
    public static function Getendtime($ID, $DATE)
    {
        $url = self::$BT_PANEL . '/site?action=SetEdate';
        $data = [
            'id' => $ID,
            'edate' => explode(' ', $DATE)[0],
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    /**
     * 删除网站数据
     */
    public static function GetDeleteSite($ID, $NAME)
    {
        $url = self::$BT_PANEL . '/site?action=DeleteSite';
        $data = [
            'id' => $ID,
            'webname' => $NAME,
            'ftp' => 1,
            'database' => 1,
            'path' => 1
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //调整流量并发限制
    public static function GetSetLimitNet($ID, $MainframeData)
    {
        $url = self::$BT_PANEL . '/site?action=SetLimitNet';
        $data = [
            'id' => $ID,
            'perserver' => $MainframeData['concurrencyall'],
            'perip' => $MainframeData['concurrencyip'],
            'limit_rate' => $MainframeData['traffic'],
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //切换网站状态 1启用，2关闭
    public static function GetSwitchState($ID, $NAME, $TYPE)
    {
        $url = self::$BT_PANEL . ($TYPE == 1 ? '/site?action=SiteStart' : '/site?action=SiteStop');
        $data = [
            'id' => $ID,
            'name' => $NAME,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取网站子目录列表
    public static function GetDirBinding($ID)
    {
        $url = self::$BT_PANEL . '/site?action=GetDirBinding';
        $data = [
            'id' => $ID,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //绑定域名,根目录模式
    public static function GetAddDomain($ID, $NAME, $DOMAIN)
    {
        $url = self::$BT_PANEL . '/site?action=AddDomain';
        $data = [
            'id' => $ID,
            'webname' => $NAME,
            'domain' => $DOMAIN,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //绑定域名,子目录模式
    public static function GetAddDirBinding($ID, $DOMAIN, $DIRNAME)
    {
        $url = self::$BT_PANEL . '/site?action=AddDirBinding';
        $data = [
            'id' => $ID,
            'domain' => $DOMAIN,
            'dirName' => $DIRNAME,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //Session 隔离
    public static function GetSessionPath($ID)
    {
        $url = self::$BT_PANEL . '/config?action=set_php_session_path';
        $data = [
            'id' => $ID,
            'act' => 1
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //删除域名
    public static function GetDelDomain($ID, $NAME, $DOMAIN, $PORT)
    {
        $url = self::$BT_PANEL . '/site?action=DelDomain';
        $data = [
            'id' => $ID,
            'webname' => $NAME,
            'domain' => $DOMAIN,
            'port' => $PORT,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //删除子目录域名
    public static function GetDelDirBinding($ID)
    {
        $url = self::$BT_PANEL . '/site?action=DelDirBinding';
        $data = [
            'id' => $ID,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取子目录伪静态规则数据
    public static function GetDirRewrite($ID, $ADD = false)
    {
        $url = self::$BT_PANEL . '/site?action=GetDirRewrite';
        $data = [
            'id' => $ID,
            'add' => $ADD
        ];
        if ($ADD == false) unset($data['add']);
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取内置伪静态规则内容
    public static function GetFileBody($Path)
    {
        $url = self::$BT_PANEL . '/files?action=GetFileBody';
        $data = [
            'path' => $Path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //存储子目录伪静态规则
    public static function GetSaveFileBody($path, $data, $encoding = 'utf-8')
    {
        $url = self::$BT_PANEL . '/files?action=SaveFileBody';
        $data = [
            'path' => $path,
            'encoding' => $encoding,
            'data' => $data
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取文件列表
    public static function GetDir($path, $page = 1, $limit = 200)
    {
        $url = self::$BT_PANEL . '/files?action=GetDir&tojs=GetFiles&p=' . $page . '&showRow=' . $limit;
        $data = [
            'path' => $path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取文件夹大小
    public static function GetPathSize($path)
    {
        $url = self::$BT_PANEL . '/files?action=get_path_size';
        $data = [
            'path' => $path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //批量操作文件
    public static function SetBatchData($path, $type, $data)
    {
        $url = self::$BT_PANEL . '/files?action=SetBatchData';
        $data = [
            'path' => $path,
            'type' => (int)$type,
            'data' => json_encode($data),
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //下载远程文件
    public static function GetDownloadFile($path, $curl, $filename)
    {
        $url = self::$BT_PANEL . '/files?action=DownloadFile';
        $data = [
            'path' => $path,
            'url' => $curl,
            'filename' => $filename,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    /**
     * @param $f_path
     * @param $f_name
     * @param $f_size
     * @param $f_start
     * @param $blob
     * @param string $m
     * @param string $f
     * @return mixed
     * 本地文件上传
     */
    public static function GetUpdate($f_path, $Data)
    {
        $url = self::$BT_PANEL . '/files?action=upload';
        $data['f_path'] = $f_path;
        $data['f_name'] = $Data['file']['name'];
        $data['f_size'] = $Data['file']['size'];
        $data['f_start'] = 0;
        $Data['blob'] = $Data['file'];
        unset($Data['file']);
        $Curlfiles = [];
        foreach ($Data as $key => $value) {
            $Curlfiles[$key] = new CURLFile(realpath($Data[$key]['tmp_name']), $Data[$key]['type'], $Data[$key]['name']);
        }
        $data = array_merge($data, $Curlfiles);
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //新建文件
    public static function GetCreateFile($Path)
    {
        $url = self::$BT_PANEL . '/files?action=CreateFile';
        $data = [
            'path' => $Path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //新建目录
    public static function GetCreateDir($Path)
    {
        $url = self::$BT_PANEL . '/files?action=CreateDir';
        $data = [
            'path' => $Path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //备份文件,批量
    public static function GetZip($path, $z_type, $dfile, $sfile)
    {
        $url = self::$BT_PANEL . '/files?action=Zip';
        $data = [
            'sfile' => $sfile,
            'dfile' => $dfile,
            'z_type' => $z_type,
            'path' => $path
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //执行队列文件
    public static function GetTaskLists()
    {
        $url = self::$BT_PANEL . '/task?action=get_task_lists';
        $data = [
            'status' => -3,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //文件粘贴验证
    public static function GetCheckExistsFiles($Path)
    {
        $url = self::$BT_PANEL . '/files?action=CheckExistsFiles';
        $data = [
            'dfile' => $Path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //粘贴全部文件
    public static function GetBatchPaste($Path)
    {
        $url = self::$BT_PANEL . '/files?action=BatchPaste';
        $data = [
            'path' => $Path,
            'type' => (empty((int)$_SESSION['BatchData']) ? 1 : $_SESSION['BatchData'])
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //文件重命名
    public static function GetMvFile($sfile, $dfile)
    {
        $url = self::$BT_PANEL . '/files?action=MvFile';
        $data = [
            'sfile' => $sfile,
            'dfile' => $dfile,
            'rename' => true,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //删除单文件
    public static function GetDeleteFile($Path)
    {
        $url = self::$BT_PANEL . '/files?action=DeleteFile';
        $data = [
            'path' => $Path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //创建分享外链
    public static function GetShareDownload($filename, $ps, $s = 24)
    {
        $url = self::$BT_PANEL . '/files?action=create_download_url';
        $data = [
            'filename' => $filename,
            'ps' => $ps,
            'password' => md5(time() . '分享密码'),
            'expire' => $s
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //解压文件！
    public static function GetUnZip($sfile, $dfile, $type, $coding, $password = null)
    {
        $url = self::$BT_PANEL . '/files?action=UnZip';
        $data = [
            'sfile' => $sfile,
            'dfile' => $dfile,
            'type' => $type,
            'coding' => $coding,
            'password' => $password,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取当前站点的php版本
    public static function GetSitePHPVersion($Path)
    {
        $url = self::$BT_PANEL . '/site?action=GetSitePHPVersion';
        $data = [
            'siteName' => $Path,
        ];
        $result = self::HttpPostCookie($url, $data);
        $data = json_decode($result, true);
        return $data;
    }

    //获取PHP版本列表
    public static function GetPHPVersion()
    {
        $url = self::$BT_PANEL . '/site?action=GetPHPVersion';
        $result = self::HttpPostCookie($url, []);
        $data = json_decode($result, true);
        return $data;
    }

    //Get通用方法
    public static function Get($url, $data)
    {
        $url = self::$BT_PANEL . $url;
        $result = self::HttpPostCookie($url, $data);
        return json_decode($result, true);
    }
}
