<?php   
/* CAT:Pie charts */

/* pChart library inclusions */
Yii::import('application.extensions.pChart.yiipData');
Yii::import('application.extensions.pChart.yiipDraw');
Yii::import('application.extensions.pChart.yiipPie');
Yii::import('application.extensions.pChart.yiipImage');

$Notas = DefaultController::getNotasCreditoXCaract();

/* Create and populate the pData object */
$MyData = new yiipData();   

//aqui van los valores de cada caracteristica 
$MyData->addPoints($Notas['valores'],"ScoreA"); 

$MyData->setSerieDescription("Notas","Notas por caracteristica");

//Aqui van los nombres de cada caracteristica
$MyData->addPoints($Notas['tiposnotas'],"Labels"); 

$MyData->setAbscissa("Labels");

/* Create the pChart object */
$myPicture = new yiipImage(1080,432,$MyData);

/* Draw a solid background */
$myPicture->drawFilledRectangle(0,0,1080,432, array(
	"R"=>255, 
	"G"=>255, 
	"B"=>255, 
	"Dash"=>1, 
	"DashR"=>255, 
	"DashG"=>255, 
	"DashB"=>255
));

/* Write the picture title */  
//Descomentar
$myPicture->setFontProperties(array(
	"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
	"FontSize"=>22
));

$myPicture->drawText(1,35,"Notas generadas por característica",array(
	"R"=>0,
	"G"=>0,
	"B"=>0
));

//Descomentar
$myPicture->setFontProperties(array(
	"FontName"=>"themes/v2/fonts/Roboto-Regular-webfont.ttf",
	"FontSize"=>10,
	"R"=>0,
	"G"=>0,
	"B"=>0
)); 

/* Create the pPie object */ 
$PieChart = new yiipPie($myPicture,$MyData);

/* Draw an AA pie chart */ 
$PieChart->draw3DPie(380,265,array(
	"Radius"=>120,
	"DrawLabels"=>TRUE,
	"LabelStacked"=>TRUE,
	"DataGapAngle"=>8,
	"DataGapRadius"=>6,
	"Border"=>TRUE,
	"BorderR"=>0,
	"BorderG"=>0,
	"BorderB"=>0
));

/* Draw a splitted pie chart */ 
$PieChart->draw2DPie(910,265,array(
	"Radius"=>120,
	"WriteValues"=>PIE_VALUE_PERCENTAGE,
	"ValueR"=>0,"ValueG"=>0,"ValueB"=>0,
	"DataGapAngle"=>8,
	"DataGapRadius"=>6,
	"Border"=>TRUE,
	"BorderR"=>200,
	"BorderG"=>200,
	"BorderB"=>200
));

$PieChart->drawPieLegend(680,15,array(
	"Style"=>LEGEND_ROUND,
	"Mode"=>LEGEND_VERTICAL
));

echo base64_encode($myPicture->toBase64());
?>