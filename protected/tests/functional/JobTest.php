<?php

class JobTest extends WebTestCase
{
	public $fixtures=array(
		'jobs'=>'Job',
	);

	public function testShow()
	{
		$this->open('?r=job/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=job/create');
	}

	public function testUpdate()
	{
		$this->open('?r=job/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=job/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=job/index');
	}

	public function testAdmin()
	{
		$this->open('?r=job/admin');
	}
}
