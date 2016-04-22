<?php
/**
 * 飞信好友
 * @author liuhui
 * @time 2014.03.27
 */
class FetionAction extends HasLoginAction
{
    /**
     * 好友列表
     */
    public function index()
    {
        $fetionM = new FetionUserModel(); // 实例化fetionuser对象
        import('ORG.Util.Page');// 导入分页类
        $count      = $fetionM->count();// 查询满足要求的总记录数
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $fetionList = $fetionM->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('fetionList',$fetionList);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
 
    }
    
    /**
     * 好友添加
     */
    public function add()
    {
        if(isset($_POST['btn_tj']))
        {
            $fetionM = new FetionUserModel();
            if($fetionM->create()){
                $fetionM->add_time = time();
                $cityName = $_POST['cityname'];
                $cityArr  = include './Public/data/citycode.php';
                $cityCode = $cityArr[$cityName];
                if(empty($cityCode)){
                    $this->error('所选城市不存在');
                }else{
                    $fetionM->citycode = $cityCode;
                    if(isset($_POST['send_weather']))
                        $fetionM->send_weather = 1;
                    else
                        $fetionM->send_weather = 0;
                    
                    if(isset($_POST['send_news']))
                        $fetionM->send_news = 1;
                    else 
                        $fetionM->send_news = 0;
                    
                    if(isset($_POST['send_jizhuanwan']))
                        $fetionM->send_jizhuanwan = 1;
                    else 
                        $fetionM->send_jizhuanwan = 0;
                    
                    $res = $fetionM->add();
                    if($res){
                            $this->success('添加成功', U('Admin/index'));
                    }else{
                            $this->error('添加失败',U('Fetion/add'));
                    }
                }
                
            }else{
                    $this->error($fetionM->getError());
            }
        }
        $this->display();
    }
    
    /**
     * 编辑好友信息
     */
    public function edit()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $fetionM = new FetionUserModel();
        $userInfo = $fetionM->find($id);
        if(isset($_POST['btn_edit']))
        {
            if($fetionM->create()){
                
                $cityName = $_POST['cityname'];
                $cityArr  = include './Public/data/citycode.php';
                $cityCode = $cityArr[$cityName];
                if(empty($cityCode)){
                    $this->error('所选城市不存在');
                }else{
                    $fetionM->citycode = $cityCode;
                    if(isset($_POST['send_weather']))
                        $fetionM->send_weather = 1;
                    else
                        $fetionM->send_weather = 0;
                    
                    if(isset($_POST['send_news']))
                        $fetionM->send_news = 1;
                    else 
                        $fetionM->send_news = 0;
                    
                   if(isset($_POST['send_jizhuanwan']))
                        $fetionM->send_jizhuanwan = 1;
                    else 
                        $fetionM->send_jizhuanwan = 0;
                    
                    $res = $fetionM->save();
                    if($res){
                            $this->success('添加成功', U('Admin/index'));
                    }else{
                            $this->error('添加失败',U('Fetion/add'));
                    }
                }
                
            }else{
                    $this->error($fetionM->getError());
            }
        }
        
        $this->assign('userInfo', $userInfo);
        $this->display();
    }
}
