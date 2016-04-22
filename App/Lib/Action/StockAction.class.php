<?php

/**
 * 股票controller
 * @author liuhui
 * @since 2015.10.16
 */
class StockAction extends HasLoginAction
{
    public function index()
    {
        $stockM = new StockModel();
        $StockInfo = $stockM->select();
        
        $this->assign('stockInfo', $StockInfo);
        $this->display(); 
    }
    
    /**
     * 添加
     */
    public function add()
    {
        if(isset($_POST['btn_tj'])) {
            $stockM = new StockModel();
			if($stockM->create()){
				
                $res = $stockM->add();
				if($res){
					$this->success('添加成功', U('Admin/index'));
				}else{
					$this->error('添加失败',U('Stock/add'));
				}
			}else{
				$this->error($stockM->getError());
			}
        }
        $this->display();
    }
    
    /**
     * 添加股票预警
     */
    public function addwarn()
    {
        if(isset($_POST['btn_tj'])){
            $stockWarningM = new StockWarningModel();

            $warningInfo = $stockWarningM->where("code='{$_POST['code']}'")->find();
            if(!$warningInfo){    
                if($stockWarningM->create()){
                    $stockWarningM->add_time = date("Y-m-d H:i:s");
                    $res = $stockWarningM->add();
                    if($res){
                        $this->success('添加成功', U('stock/warninglist'));
                    }else{
                        $this->error('添加失败',U('Stock/addwarn'));
                    }
                }else{
                    $this->error($stockWarningM->getError());
                }
            }else{
                $this->error('该code已经存在');
            }
            // 查找该股票是否存在，存在才添加
        }
        $this->display();
    }
    
    /**
     * 价格预警列表
     */
    public function warninglist()
    {
        $stockWarningM = new StockWarningModel();
        $stockAll = $stockWarningM->select();
        
        $this->assign('stockAll', $stockAll);
        $this->display();
    }
}

