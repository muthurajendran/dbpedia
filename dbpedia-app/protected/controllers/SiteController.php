<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionData(){
		$output = Yii::app()->curl->setOptions(array(
			CURLOPT_HTTPHEADER => array('Accept: application/json'),
			CURLOPT_FOLLOWLOCATION => TRUE
			))->get('http://dbpedia.org/resource/Los_Angeles');

		if($output){
			$result = json_decode($output,true);
			$url = 'http://dbpedia.org/resource/Los_Angeles';

			$data = array();
			//Get the total population
			foreach ($result[$url]['http://dbpedia.org/ontology/populationTotal'] as $row) {
				$temp['type'] =  $row['type'];
				$temp['value'] = $row['value'];
				$data['populationTotal'][] = $temp;
			}

			//Get the area
			foreach ($result[$url]['http://dbpedia.org/ontology/areaTotal'] as $row) {
				$temp['type'] =  $row['type'];
				$temp['value'] = $row['value'];
				$data['areaTotal'][] = $temp;
			}
			//var_dump($result[$url]);

			//Get the Km or Density
			if(isset($result[$url]['http://dbpedia.org/property/populationDensityKm'])){
				foreach ($result[$url]['http://dbpedia.org/property/populationDensityKm'] as $row) {
					$temp['type'] =  $row['type'];
					$temp['in'] =  'Km';
					$temp['value'] = $row['value'];
					$data['density'][] = $temp;
				}
			} else if(isset($result[$url]['http://dbpedia.org/property/populationDensitySqMi'])){
				foreach ($result[$url]['http://dbpedia.org/property/populationDensitySqMi'] as $row) {
					$temp['type'] =  $row['type'];
					$temp['in'] =  'SqMi';
					$temp['value'] = $row['value'];
					$data['density'][] = $temp;
				}
			}
			$data['success'] = 1;
			echo json_encode($data);
		}else{
			$data['success'] = 0;
			$data['message'] = "System not available";
			echo json_encode($data);
		}
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}