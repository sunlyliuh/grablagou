<?php
/**
 * 文件上传
 * @author liuhui
 * @time 2014.1.13
 */
class FileAction extends HasLoginAction{
    
    function upload()
    {

        //定义允许上传的文件扩展名
        $ext_arr = array(
                'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
                'flash' => array('swf', 'flv'),
                'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
                'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        //最大文件大小
        $max_size = 1000000;

        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
                switch($_FILES['imgFile']['error']){
                        case '1':
                                $error = '超过php.ini允许的大小。';
                                break;
                        case '2':
                                $error = '超过表单允许的大小。';
                                break;
                        case '3':
                                $error = '图片只有部分被上传。';
                                break;
                        case '4':
                                $error = '请选择图片。';
                                break;
                        case '6':
                                $error = '找不到临时目录。';
                                break;
                        case '7':
                                $error = '写文件到硬盘出错。';
                                break;
                        case '8':
                                $error = 'File upload stopped by extension。';
                                break;
                        case '999':
                        default:
                                $error = '未知错误。';
                }
                $this->_alert($error);
        }

        //有上传文件时
        if (empty($_FILES) === false) {
                //原文件名
                $file_name = $_FILES['imgFile']['name'];
                //服务器上临时文件名
                $tmp_name = $_FILES['imgFile']['tmp_name'];
                //文件大小
                $file_size = $_FILES['imgFile']['size'];
                //检查文件名
                if (!$file_name) {
                        $this->_alert("请选择文件。");
                }

                //检查文件大小
                if ($file_size > $max_size) {
                        $this->_alert("上传文件大小超过限制。");
                }
               
               
                //获得文件扩展名
                $temp_arr = explode(".", $file_name);
                $file_ext = array_pop($temp_arr);
                $file_ext = trim($file_ext);
                $file_ext = strtolower($file_ext);
                //检查扩展名
                if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                        $this->_alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
                }
                $remoteFile = date('Ymd').$file_name;
                $s = new SaeStorage();
                $fileUrl = $s->upload( 'blogimage' , $remoteFile ,  $_FILES['imgFile']['tmp_name']);

                header('Content-type: text/html; charset=UTF-8');

                echo json_encode(array('error' => 0, 'url' => $fileUrl));
                exit;
        }


    }
    
    private function _alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 1, 'message' => $msg));
	exit;
    }
}
