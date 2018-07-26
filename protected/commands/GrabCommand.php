<?php
/**
 * 抓取数据计划任务
 * @调用方式 /home/wwwroot/default/talk/grablagou/trunk/protected/yiic grab action
 */
class GrabCommand extends CConsoleCommand {

    /**
     * 抓取拉钩数据
     * @执行方式 /home/wwwroot/default/talk/grablagou/trunk/protected/yiic grab lagou
     */
    public function actionLagou()
    {
        
        $jobArr  = array('php','Java','C','C++','Android','iOS','python');
        $cityArr = array('北京','上海','广州','深圳','杭州','成都','武汉');
        $jobmodel = new Job;
        $jobType = $jobArr[6];
        for($i=0; $i<count($cityArr); $i++){
            $city    = $cityArr[$i];
            for($page=1; $page<=10000;$page++){
                $res = $jobmodel->getJob($city, $jobType, $page);
                sleep(3);
                if($res){
                    $jobArr = json_decode($res,true);
                    if($jobArr['success'] == 1){
                        $pageCount = $jobArr['content']['positionResult']['totalCount'];
                        if($page > $pageCount){
                            echo 'grab over!';break;
                        }else{
                            echo "grab page:{$page}\n";
                        }
        //                print_r($jobArr['content']['result']);exit;
                        foreach($jobArr['content']['positionResult']['result'] as $result){
                            unset($result['companyLabelList']);
//                            $this->_writeLog('grab2.log', implode(',',$result));
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
                                $_job->company_name= $result['companyFullName'];
                                $_job->job_nature  = $result['jobNature'];
                                $_job->education   = $result['education'];
                                $_job->finance_stage = $result['financeStage'];
                                $_job->company_size = $result['companySize'];
                                $_job->lagou_create = $result['createTime'];
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

                        echo "grab success \n";
                    }else{
                        echo "lagou接口返回失败\n";
                    }
                }else{
                    echo "抓取返回失败\n";
                }
            }
        }
    }
    
    /**
     * etl清洗数据
     * /home/wwwroot/default/talk/grablagou/trunk/protected/yiic grab etl
     */
    public function actionEtl()
    {
        $criteria = new CDbCriteria;    
        $totalsql = "SELECT count(*) as total FROM tbl_job";    
        $jobTotal = Yii::app()->db->createCommand($totalsql)->queryAll();

        $total = $jobTotal[0]['total'];
        $pages = new CPagination($total);        
        $pageSize = 40;
        
        $pages->pageSize = $pageSize;
        $totalPage = ceil($total/$pageSize);
        $cleanJobModel = new CleanJob;
        for($i=1; $i<= $totalPage; $i++){
            $currentPage = $i;
            $pages->setCurrentPage($currentPage);
            $pages->applylimit($criteria);
            $sql = "SELECT * FROM tbl_job order by id asc";   
            $model=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");    
            $model->bindValue(':offset', $pages->currentPage*$pages->pageSize);    
            $model->bindValue(':limit', $pages->pageSize);    
            $jobArr =$model->queryAll();
            //var_dump($jobArr);
            echo 'start to handel page'.$i."\n";
            
            foreach($jobArr as $job)
            {
                $_cleanjob = clone $cleanJobModel;
                //查找对应的id是否存在
                $exist = $_cleanjob->exists("position_id=:position_id",array(':position_id'=>$job['position_id']));
                if(!$exist){
                    $_cleanjob->position_id = $job['position_id'];
                    $_cleanjob->job_type    = CleanJob::getJobTypeInt($job['job_type']);
                    $_cleanjob->city        = CleanJob::getIntCity($job['city']);
                    $salaryArr              = CleanJob::getSalaryArr($job['salary']);
                    $_cleanjob->salary_min  = $salaryArr['min_salary'];
                    $_cleanjob->salary_max  = $salaryArr['max_salary'];
                    $_cleanjob->company_name= $job['company_name'];
                    $_cleanjob->job_nature  = $job['job_nature'];
                    $_cleanjob->education   = CleanJob::getIntEducation($job['education']);
                    $_cleanjob->finance_stage= CleanJob::getIntFinanceStage($job['finance_stage']);
                    $_cleanjob->company_size = CleanJob::getIntCompanySize($job['company_size']);
                    $_cleanjob->createTime   = date("Y-m-d H:i:s");

                    $res = $_cleanjob->save();
                    if($res){
                        echo 'insert success'.$_cleanjob->position_id."\n";
                    }else{
                        var_dump($_cleanjob->getErrors()) ;exit;
                        echo 'insert error'.$_cleanjob->position_id."\n";
                    }
                }else{
                    echo $job['position_id']." has exist\n";
                }
            }
            sleep(2);
            echo "sleep 2 seconds\n";
        }
        
        echo 'tase over';
    }
    
    
    private function _writeLog($fileName,$content){
        $filePath = dirname(__FILE__);
        file_put_contents($filePath.'/'.$fileName, $content."\r\n",FILE_APPEND);
    }

}

