<?php
/**
 * 抓取数据计划任务
 * @调用方式 /home/wwwroot/default/talk/datastatistic/protected/yiic grab action
 */
class GrabCommand extends CConsoleCommand {

    /**
     * 抓取拉钩数据
     * @执行方式 /home/wwwroot/default/talk/datastatistic/protected/yiic grab lagou
     */
    public function actionLagou()
    {
        $jobmodel = new Job;
        $jobType = 'php';
        $city    = '北京';
        for($page=1; $page<=10000;$page++){
        $res = $jobmodel->getJob($city, $jobType, $page);
        if($res){
            $jobArr = json_decode($res,true);
            if($jobArr['success'] == 1){
                $pageCount = $jobArr['content']['totalPageCount'];
                if($page > $pageCount){
                    echo 'grab over!';break;
                }else{
                    echo "grab page:{$page}\n";
                }
//                print_r($jobArr['content']['result']);exit;
                foreach($jobArr['content']['result'] as $result){
                    unset($result['companyLabelList']);
                    $this->_writeLog('grab.log', implode(',',$result));
                    $_job = clone $jobmodel;
                    //查找对应的id是否存在
                    $exist = $_job->exists("position_id=:position_id",array(':position_id'=>$result['positionId']));
                    if(!$exist){
                        $_job->position_id = $result['positionId'];
                        $_job->job_type    = $jobType;
                        $_job->city        = $result['city'];
                        $_job->salary      = $result['salary'];
                        $_job->salary_min  = 0;
                        $_job->salary_max  = 0;
                        $_job->company_name= $result['companyName'];
                        $_job->job_nature  = $result['jobNature'];
                        $_job->education   = $result['education'];
                        $_job->finance_stage = $result['financeStage'];
                        $_job->company_size = $result['companySize'];
                        $_job->createTime  = date('Y-m-d H:i:s');
                        $_job->position_name= $result['positionName'];
                        $res = $_job->save();
                        if($res){
                            echo $result['positionId'].'=='."insert success \n";
                        }else{
                            echo $result['positionId'].'=='."insert error\n";
                        }
                    }else{
                        echo $result['positionId'].'=='."has exist \n";
                    }
                }
                sleep(5);
                echo "grab success \n";
            }else{
                echo "lagou接口返回失败\n";
            }
        }else{
            echo "抓取返回失败\n";
        }
        }
    }
    
    /**
     * 连接数据库测试
     * /home/wwwroot/default/talk/datastatistic/protected/yiic grab test
     */
    public function actionTest(){
        for($i=1;$i<=10;$i++){
            echo $i."\n";
            sleep(2);
        }
    }
    
    private function _writeLog($fileName,$content){
        $filePath = dirname(__FILE__);
        file_put_contents($filePath.'/'.$fileName, $content."\r\n",FILE_APPEND);
    }

}

