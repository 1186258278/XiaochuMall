<?php
// +----------------------------------------------------------------------
// | Project: xc
// +----------------------------------------------------------------------
// | Creation: 2022/9/27
// +----------------------------------------------------------------------
// | Filename: PictureUpload.php
// +----------------------------------------------------------------------
// | Explain: 图片上传操作类
// +----------------------------------------------------------------------
namespace extend;

use lib\Hook\Hook;

class PictureUpload
{

    //uploadCashWithdrawChart 上传提现收款图
    public static function uploadCashWithdrawChart()
    {
        global $UserData, $_QET, $conf;
        if ($_QET['file']['type'] !== 'image/png' && $_QET['file']['type'] !== 'image/gif' && $_QET['file']['type'] !== 'image/jpeg') dies(-1, '只可上传png/jpg/gif类型的图片文件！');
        $timestamp = md5($UserData['id'] . '晴玖');
        mkdirs('../assets/img/withdraw/' . $timestamp . '/');
        mkdirs('../assets/img/withdrawTem/' . $timestamp . '/'); //临时目录
        $image = $UserData['id'] . '.png';
        $imageTem = RandomNumber() . '.png';
        move_uploaded_file($_QET['file']['tmp_name'], '../assets/img/withdrawTem/' . $timestamp . '/' . $imageTem);
        $images = ROOT . 'assets/img/withdrawTem/' . $timestamp . '/' . $imageTem;
        new ImgThumbnail($images, $conf['compression'], $images, 2);
        //移动
        if (!copy($images, ROOT . 'assets/img/withdraw/' . $timestamp . '/' . $image)) {
            return "/assets/img/404.gif";
        }
        unlink($images);

        Hook::execute('UploadCashWithdrawChart', [
            'src' => '/assets/img/withdraw/' . $timestamp . '/' . $image,
        ]);
        return '/assets/img/withdraw/' . $timestamp . '/' . $image;
    }

    //uploadPictureUser 用户单图片上传
    public static function uploadPictureUser()
    {
        global $conf, $_QET;
        if ($_QET['file']['type'] !== 'image/png' && $_QET['file']['type'] !== 'image/gif' && $_QET['file']['type'] !== 'image/jpeg') dies(-1, '只可上传png/jpg/gif类型的图片文件！');
        $timestamp = date('Ymd');
        mkdirs(ROOT . "assets/img/image/" . $timestamp . '/');
        mkdirs(ROOT . "assets/img/imageTem/" . $timestamp . '/');
        $TmpName = md5_file($_QET['file']['tmp_name']);
        $ImageName = RandomNumber();
        switch ($_QET['file']['type']) {
            case 'image/gif':
                $ImageName .= '.gif';
                $TmpName .= '.gif';
                break;
            case 'image/jpeg':
                $ImageName .= '.jpeg';
                $TmpName .= '.jpeg';
                break;
            case 'image/png':
            default:
                $ImageName .= '.png';
                $TmpName .= '.png';
                break;
        }
        move_uploaded_file($_QET['file']["tmp_name"], ROOT . 'assets/img/imageTem/' . $timestamp . '/' . $ImageName);
        $images = ROOT . 'assets/img/imageTem/' . $timestamp . '/' . $ImageName;
        new ImgThumbnail($images, $conf['compression'], $images, 2);
        //移动
        if (!copy($images, ROOT . 'assets/img/image/' . $timestamp . '/' . $TmpName)) {
            return "/assets/img/404.png";
        }
        unlink($images);
        $images = '/assets/img/image/' . $timestamp . '/' . $TmpName;

        Hook::execute('UploadLeafletPicture', [
            'src' => $images,
            'type' => ($_QET['type'] == 1 ? 1 : 2)
        ]);
        if (isset($_QET['type']) && (int)$_QET['type'] === 2) {
            dier([
                'location' => ImageUrl($images),
            ]);
        }
        return $images;
    }

    //uploadPictureUserArr 多图片上传
    public static function uploadPictureUserArr()
    {
        global $conf, $_QET;
        unset($_QET['act']);
        $ImageArr = [];
        $timestamp = date('Ymd');
        mkdirs(ROOT . "assets/img/image/" . $timestamp . '/');
        mkdirs(ROOT . "assets/img/imageTem/" . $timestamp . '/');
        foreach ($_QET as $key => $value) {
            if ($value['type'] !== 'image/png' && $value['type'] !== 'image/gif' && $value['type'] !== 'image/jpeg') dies(-1, '只可上传png/jpg/gif类型的图片文件！');
            $TmpName = md5_file($value['tmp_name']);
            $ImageName = RandomNumber();
            switch ($value['type']) {
                case 'image/gif':
                    $ImageName .= '.gif';
                    $TmpName .= '.gif';
                    break;
                case 'image/jpeg':
                    $ImageName .= '.jpeg';
                    $TmpName .= '.jpeg';
                    break;
                case 'image/png':
                default:
                    $ImageName .= '.png';
                    $TmpName .= '.png';
                    break;
            }
            move_uploaded_file($value["tmp_name"], ROOT . 'assets/img/imageTem/' . $timestamp . '/' . $ImageName);
            $images = ROOT . 'assets/img/imageTem/' . $timestamp . '/' . $ImageName;
            new ImgThumbnail($images, $conf['compression'], $images, 2);
            //移动
            if (!copy($images, ROOT . 'assets/img/image/' . $timestamp . '/' . $TmpName)) {
                continue;
            }
            unlink($images);
            $imagesEND = '/assets/img/image/' . $timestamp . '/' . $TmpName;
            $ImageArr[] = ['src' => ImageUrl($imagesEND), 'size' => $value['size'] / 1000 . 'kb', 'name' => $value['name']];
        }
        Hook::execute('UploadMultipleImage', [
            'array' => $ImageArr,
        ]);
        return $ImageArr;
    }

    //uploadVideo 多视频上传
    public static function uploadVideo($Data)
    {
        $ViewArr = [];
        $timestamp = date('Ymd');
        $File = ROOT . 'assets/video/' . $timestamp . '/';
        mkdirs($File);
        foreach ($Data as $value) {
            $VideoName = md5_file($value["tmp_name"]) . '.mp4';
            if ($value['type'] !== 'video/mp4') {
                dies(-1, '视频格式不正确,仅支持MP4格式视频!');
            }
            move_uploaded_file($value["tmp_name"], $File . $VideoName);
            $Video = $File . $VideoName;
            $ViewArr[] = ['src' => $Video, 'size' => $value['size'] / 1000 . 'kb', 'name' => $value["name"]];
        }
        Hook::execute('MultipleVideoUpload', [
            'array' => $ViewArr,
        ]);
        return [
            'code' => 1,
            'msg' => '视频上传成功,本次共成功上传' . count($ViewArr) . '个视频',
            'SrcArr' => $ViewArr,
        ];
    }
}