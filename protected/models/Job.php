<?php

/**
 * This is the model class for table "{{job}}".
 *
 * The followings are the available columns in table '{{job}}':
 * @property integer $id
 * @property integer $position_id
 * @property string $job_type
 * @property string $city
 * @property string $salary
 * @property integer $salary_min
 * @property integer $salary_max
 * @property string $company_name
 * @property string $job_nature
 * @property string $education
 * @property string $finance_stage
 * @property string $company_size
 * @property string $createTime
 * @property string $position_name
 */
class Job extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{job}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('position_id, job_type, city, salary, salary_min, salary_max, company_name, job_nature, education, finance_stage, company_size, createTime, position_name', 'required'),
			array('position_id, salary_min, salary_max', 'numerical', 'integerOnly'=>true),
			array('job_type', 'length', 'max'=>30),
			array('city, salary, job_nature', 'length', 'max'=>50),
			array('company_name, education, finance_stage, company_size, position_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, position_id, job_type, city, salary, salary_min, salary_max, company_name, job_nature, education, finance_stage, company_size, createTime, position_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'position_id' => 'Position',
			'job_type' => 'Job Type',
			'city' => 'City',
			'salary' => 'Salary',
			'salary_min' => 'Salary Min',
			'salary_max' => 'Salary Max',
			'company_name' => 'Company Name',
			'job_nature' => 'Job Nature',
			'education' => 'Education',
			'finance_stage' => 'Finance Stage',
			'company_size' => 'Company Size',
			'createTime' => 'Create Time',
			'position_name' => 'Position Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('position_id',$this->position_id);

		$criteria->compare('job_type',$this->job_type,true);

		$criteria->compare('city',$this->city,true);

		$criteria->compare('salary',$this->salary,true);

		$criteria->compare('salary_min',$this->salary_min);

		$criteria->compare('salary_max',$this->salary_max);

		$criteria->compare('company_name',$this->company_name,true);

		$criteria->compare('job_nature',$this->job_nature,true);

		$criteria->compare('education',$this->education,true);

		$criteria->compare('finance_stage',$this->finance_stage,true);

		$criteria->compare('company_size',$this->company_size,true);

		$criteria->compare('createTime',$this->createTime,true);

		$criteria->compare('position_name',$this->position_name,true);

		return new CActiveDataProvider('Job', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return Job the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function getJob($city,$kd='php',$page=1)
    {
        $url = "http://www.lagou.com/jobs/positionAjax.json?city=".urlencode($city); 
        
        $header = array(
          'Referer:http://www.lagou.com/jobs/list_php?labelWords=&fromSearch=true&suginput=',
          'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36');
        $postData = array(
            "first" => "false",
            "pn" => $page,
            "kd" => $kd,
        );
        $msg = $this->curl_post($header,$postData,$url);
        return $msg;
    }
    
    /**
     * curl post数据
     * @param type $header
     * @param type $data
     * @param type $url
     * @return int
     */
    function curl_post($header,$data,$url)
    {
        $ch = curl_init();
        $res= curl_setopt ($ch, CURLOPT_URL,$url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        $result = curl_exec ($ch);
        curl_close($ch);
        if ($result == NULL) {
            return 0;
        }
        return $result;
    }
}