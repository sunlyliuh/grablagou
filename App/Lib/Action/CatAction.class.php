<?php
/**
 * 文章分类添加
 * @author liuhui
 * @since 2013.11.16
 */
class CatAction extends HasLoginAction
{
    // 分类展示
    public function index()
    {
        $catM = new CatModel();
        $catInfo = $catM->select();
        
        $this->assign('catInfo', $catInfo);
        $this->display(); 
    }
    // 分类添加
    public function add() 
    {
        if(isset($_POST['btn_tj'])) {
            $catM = new CatModel();
			if($catM->create()){
				$catM->add_time = time();
                $res = $catM->add();
				if($res){
					$this->success('添加成功', U('Admin/index'));
				}else{
					$this->error('添加失败',U('Cat/add'));
				}
			}else{
				$this->error($catM->getError());
			}
        }
        $this->display();
    }
}
?>
