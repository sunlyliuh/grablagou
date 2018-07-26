<?php

class JobController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','grab','Analysis','Cityanalysis','Citysalary'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$_job=new Job;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($_job);

		if(isset($_POST['Job']))
		{
			$_job->attributes=$_POST['Job'];
			if($_job->save())
				$this->redirect(array('view','id'=>$_job->id));
		}

		$this->render('create',array(
			'model'=>$_job,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$_job=$this->loadModel();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($_job);

		if(isset($_POST['Job']))
		{
			$_job->attributes=$_POST['Job'];
			if($_job->save())
				$this->redirect(array('view','id'=>$_job->id));
		}

		$this->render('update',array(
			'model'=>$_job,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Job');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$_job=new Job('search');
		$_job->unsetAttributes();  // clear any default values
		if(isset($_GET['Job']))
			$_job->attributes=$_GET['Job'];

		$this->render('admin',array(
			'model'=>$_job,
		));
	}
    
    public function actionGrab()
    {
        $jobmodel = new Job;
        $jobType = 'C';
        $city    = '北京';
        for($page=1; $page<=10000;$page++){
        $res = $jobmodel->getJob($city, $jobType, $page=1);print_r($res);exit;
        if($res){
            $jobArr = json_decode($res,true);print_r($jobArr);exit;
            if($jobArr['success'] == 1){
                $pageCount = $jobArr['content']['totalPageCount'];
                if($page >= $pageCount){
                    echo 'grab over!';break;
                }else{
                    echo "grab page:{$page}<br/>";
                }
                foreach($jobArr['content']['result'] as $result){
                    $_job = clone $jobmodel;
                    //查找对应的id是否存在
                    $exist = $_job->exists("position_id=:position_id",array(':position_id'=>$result['positionId']));
                    if(!$exist){
                        $salaryArr = explode('-',$result['salary']);
                        $_job->position_id = $result['positionId'];
                        $_job->job_type    = $jobType;
                        $_job->city        = $result['city'];
                        $_job->salary      = $result['salary'];
                        $_job->salary_min  = str_replace(array('k','K'),'000',$salaryArr[0]);
                        $_job->salary_max  = str_replace(array('k','K'),'000',$salaryArr[1]);
                        $_job->company_name= $result['companyName'];
                        $_job->job_nature  = $result['jobNature'];
                        $_job->education   = $result['education'];
                        $_job->finance_stage = $result['financeStage'];
                        $_job->company_size = $result['companySize'];
                        $_job->createTime  = date('Y-m-d H:i:s');
                        $_job->position_name= $result['positionName'];
                        $res = $_job->save();  
                        if($res){
                            echo $result['positionId'].'=='.'insert success <br />';
                        }else{

                            echo $result['positionId'].'=='.'insert error<br />';
                        }
                    }else{
                        echo $result['positionId'].'=='.'has exist <br />';
                    }
                }
                sleep(2);
                echo 'grab success';
            }else{
                echo 'lagou接口返回失败';
            }
        }else{
            echo '抓取返回失败';
        }
        }
    }
    
    /**
     * 职位分析页面展示
     */
    public function actionAnalysis()
    {
        
        $this->render('analysis',array(
//			'city'=>$dataProvider,
		));
    }
    
    /**
     * 按城市分析各个职位的行情数据
     */
    public function actionCityanalysis()
    {
        $city = $_GET['city'];
        $totalsql = "SELECT count(*) as cnt FROM `tbl_clean_job` where city='{$city}' GROUP BY job_type ORDER BY `job_type` asc";    
        $jobTotal = Yii::app()->db->createCommand($totalsql)->queryAll();
        $jobTypeTotal = array();
        foreach($jobTotal as $val){
            array_push($jobTypeTotal, $val['cnt']);
        }
        
        $option_legend_data = array('发布需求统计');
        $option_xAxis_data = array('php','java','c','c++','android','ios');
        $line_option_series_data = array(
            $jobTypeTotal,
        );
        $barOption = CleanJob::getEchartBarOption('同一个城市不同职位的需求统计', $option_legend_data, $option_xAxis_data, $line_option_series_data, $unit='个');
        
        $result = array(
            'ret' => 'succ',
            'option'=> $barOption,
        );
        echo json_encode($result);exit;
    }
    
    /**
     * 同一个城市不同职位的工资
     */
    public function actionCitysalary()
    {
        $city = $_GET['city'];
        $totalsql = "SELECT avg(salary_max) as avg_salary FROM `tbl_clean_job` where city='{$city}' GROUP BY job_type ORDER BY `job_type` asc";    
        $jobTotal = Yii::app()->db->createCommand($totalsql)->queryAll();
        $jobTypeTotal = array();
        foreach($jobTotal as $val){
            array_push($jobTypeTotal, $val['avg_salary']);
        }
        
        $option_legend_data = array('平均工资统计');
        $option_xAxis_data = array('php','java','c','c++','android','ios');
        $line_option_series_data = array(
            $jobTypeTotal,
        );
        $barOption = CleanJob::getEchartBarOption('同一个城市不同职位的平均工资', $option_legend_data, $option_xAxis_data, $line_option_series_data, $unit='元');
        
        $result = array(
            'ret' => 'succ',
            'option'=> $barOption,
        );
        echo json_encode($result);exit;
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Job::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($_job)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='job-form')
		{
			echo CActiveForm::validate($_job);
			Yii::app()->end();
		}
	}
}
