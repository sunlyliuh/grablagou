<?php
/**
 * 用户登陆下的页面
 */
class AdminAction extends HasLoginAction
{
    public function index()
    {
        $userName = session('user_name');
        
        $this->assign('userName', $userName);
        $this->display();
    }
    
    // 添加文章
    public function addarticle()
    {
        // 获取文章分类
        $catM = new CatModel();
        $catInfo = $catM->select();
        
        if(isset($_POST['btn_tj'])) {
            $artileM = new ArticleModel();
			if($artileM->create()){
				$artileM->add_time = time();
                $res = $artileM->add();
				if($res){
//					$this->success('添加成功', U('Article/detail',array('id'=>$res)));
                    $this->success('添加成功', U('Admin/articlelist'));
				}else{
					$this->error('添加失败',U('Admin/addarticle'));
				}
			}else{
				$this->error($artileM->getError());
			}
        }
        $this->assign('catInfo', $catInfo);
        $this->display();
    }
    
    // 文章列表显示
    public function articlelist()
    {
        $articleM = new ArticleModel(); // 实例化User对象
        import('ORG.Util.Page');// 导入分页类
        $count      = $articleM->where(array('status'=>'正常'))->count();// 查询满足要求的总记录数
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $articleList = $articleM->where(array('status'=>'正常'))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($articleList as $key=>$val)
        {
            $articleList[$key]['article_id'] = $this->encodeArticleId($val['id']);
        }
        $this->assign('articleList',$articleList);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    // 文章编辑
    public function editarticle()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $articleM = new ArticleModel();
        $articleInfo = $articleM->find($id);
        // 文章分类获取
        $catM = new CatModel();
        $catInfo = $catM->select();
        
        if(isset($_POST['btn_edit'])) {
            if($articleM->create()){
                $res = $articleM->save();
                if($res) {
                    $this->success('修改成功', U('Admin/articlelist'));
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error($articleM->getError());
            }
        }
        $this->assign('articleInfo', $articleInfo);
        $this->assign('catInfo', $catInfo);
        $this->display();
    }
    
    // 查看访客
    public function visit()
    {
        $visitM = new VisitModel(); // 实例化User对象
        import('ORG.Util.Page');// 导入分页类
        $count      = $visitM->count();// 查询满足要求的总记录数
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $visitList = $visitM->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('visitList',$visitList);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    /**
     * 发送短信日志
     */
    public function fetionlog()
    {
        $fetionLogM = new FetionLogModel(); // 实例化User对象
        import('ORG.Util.Page');// 导入分页类
        $count      = $fetionLogM->count();// 查询满足要求的总记录数
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $logList = $fetionLogM->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('logList',$logList);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
}
?>
