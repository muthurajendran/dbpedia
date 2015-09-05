<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css">
	<?php 
	Yii::app()->clientScript->registerCoreScript('jquery');     
	Yii::app()->clientScript->registerCoreScript('jquery.ui');
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">DBpedia Info</a>
        </div>
      </div>
    </nav>
    
    <div class="jumbotron">

    </div>
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">

        <div class="page-header">
          <h3>DBPedia City Inofrmation</h3>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <form class="form-horizontal" id="cityForm">
          <fieldset>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="selectbasic">Choose City</label>
            <div class="col-md-4">
              <select id="selectbasic" name="selectbasic" class="form-control">
                <option value="1">Los Angeles</option>
                <option value="2">New York</option>
                <option value="3">Houston</option>
              </select>
            </div>
          </div>

          <!-- Button -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>

          </fieldset>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
        	<div class="span6 well" id="jsonData">
        		<p> Hi </p>
        	</div>
        	
        </div>
      </div>
    </div>
    <?php 
		Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl . '/js/bootstrap.min.js',CClientScript::POS_END );
    ?>
    <script type="text/javascript">

    $("#cityForm").submit(function(e) {
      e.preventDefault();
      city = $("#selectbasic option:selected").text();
      ajax_url = "http://dbpedia.org/resource/";
      ajax_url = ajax_url + city.replace(" ","_")

      //callOtherDomain(ajax_url);
      $.ajax({
        type: "GET",
        url: "index.php/site/data",
      }).done(function(data) {
        if ( console && console.log ) {
          console.log(data);
        }
        alert("done");
      }).fail(function() {
	    alert( "error" );
	  })
	  .always(function() {
	    alert( "complete" );
	  });

    });
    </script>
  </body>
</html>