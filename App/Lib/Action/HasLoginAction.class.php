<?php
/**
 * 所有类的父类，都需要继承这个进行操作
 */
class HasLoginAction extends CommonAction
{
    public function __construct() {
        // 判断是否登陆
        $userId = session('user_id');
        if(empty($userId)) {
            $this->redirect(U('Login/logout'));
        }
        parent::__construct();
    }
}
?>
