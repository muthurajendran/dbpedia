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
		if(isset($_POST['city'])){
			$city = $_POST['city'];
		} else {
			$data['success'] = 0;
			$data['message'] = "No City Selected";
			echo json_encode($data);
			die();
		}

		// $url = getUrlDbpediaAbstract($city);
		//$url = 'http://dbpedia.org/resource/'.$city;
		$url = $this->getUrlDbpediaAbstract($city);

		$output = Yii::app()->curl->setOptions(array(
			//CURLOPT_HTTPHEADER => array('Accept: application/json'),
			CURLOPT_FOLLOWLOCATION => TRUE
			))->get($url);
	
		if($output){
			$responseArray = json_decode($output,true);
			$result = $responseArray['results']['bindings'];

			foreach ($result as $value) {
					foreach ($value as $key => $value) {
						//var_dump($key);
						if(!in_array($key, array('x','abstract'))){
							$temp['type'] = $value['type'];
							$temp['value'] = $value['value'];
							$data[$key][] = $temp;
						}
					}
			}

			$data['success'] = 1;
			echo json_encode($data);
		}else{
			$data['success'] = 0;
			$data['message'] = "System not available";
			echo json_encode($data);
		}

		//Previous direct code
		// if($output){
		// 	$result = json_decode($output,true);
			

		// 	$data = array();
		// 	//Get the total population
		// 	foreach ($result[$url]['http://dbpedia.org/ontology/populationTotal'] as $row) {
		// 		$temp['type'] =  $row['type'];
		// 		$temp['value'] = $row['value'];
		// 		$data['populationTotal'][] = $temp;
		// 	}

		// 	//Get the area
		// 	foreach ($result[$url]['http://dbpedia.org/ontology/areaTotal'] as $row) {
		// 		$temp['type'] =  $row['type'];
		// 		$temp['value'] = $row['value'];
		// 		$data['areaTotal'][] = $temp;
		// 	}

		// 	//Get the Km or Density
		// 	if(isset($result[$url]['http://dbpedia.org/property/populationDensityKm'])){
		// 		foreach ($result[$url]['http://dbpedia.org/property/populationDensityKm'] as $row) {
		// 			$temp['type'] =  $row['type'];
		// 			$temp['in'] =  'Km';
		// 			$temp['value'] = $row['value'];
		// 			$data['density'][] = $temp;
		// 		}
		// 	} else if(isset($result[$url]['http://dbpedia.org/property/populationDensitySqMi'])){
		// 		foreach ($result[$url]['http://dbpedia.org/property/populationDensitySqMi'] as $row) {
		// 			$temp['type'] =  $row['type'];
		// 			$temp['in'] =  'SqMi';
		// 			$temp['value'] = $row['value'];
		// 			$data['density'][] = $temp;
		// 		}
		// 	}
		// 	$data['success'] = 1;
		// 	echo json_encode($data);
		// }else{
		// 	$data['success'] = 0;
		// 	$data['message'] = "System not available";
		// 	echo json_encode($data);
		// }
	}

//TO DO fetch otherways
public function getUrlDbpediaAbstract($term)
{
   $format = 'json';

  $query = " 
  PREFIX dbp2: <http://dbpedia.org/ontology/>
  SELECT * WHERE {
  ?x rdfs:label '".$term."'@en.
  ?x dbp2:populationTotal ?populationTotal.
  ?x dbp2:abstract ?abstract.
  ?x dbp2:populationDensity ?populationDensity.
  ?x dbp2:areaTotal ?areaTotal.
  FILTER (LANG(?abstract) = 'en')
}";
 
   $searchUrl = 'http://dbpedia.org/sparql?'
      .'query='.urlencode($query)
      .'&format='.$format;
	  
   return $searchUrl;
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