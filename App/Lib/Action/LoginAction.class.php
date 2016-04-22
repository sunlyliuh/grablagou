<?php
/**
 * 登录后台添加文章
 * @author liuhui
 * @since 2013.11.15
 */
class LoginAction extends CommonAction
{
    // 登陆
    public function index()
    {
        if(isset($_POST['btn_tj'])){
			$userM = new UserModel();
            $userName = $_POST['username'];
            $pwd = $_POST['pwd'];
            if(empty($userName) || empty($pwd)){
                $this->error('用户名或密码不能为空');
            }
            $userInfo = $userM->where(array('username'=>$userName,'password'=>md5($pwd)))->find();
            if(empty($userInfo)) {
               $this->error('用户名或密码错误');
            }
            session('user_id', $userInfo['id']);
            session('user_name', $userInfo['username']);
            $this->redirect('Admin/index');
		}
        $this->assignSeoName('用户登录');
        $this->display();
    }
    
    // 退出
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->success('您已成功退出',U('Login/index'));
    }
}
?>
