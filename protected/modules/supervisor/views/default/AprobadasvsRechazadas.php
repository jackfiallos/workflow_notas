<?php   
 /* CAT:Pie charts */

 /* pChart library inclusions */


Yii::import('application.extensions.pChart.yiipData');
Yii::import('application.extensions.pChart.yiipDraw');
Yii::import('application.extensions.pChart.yiipPie');
Yii::import('application.extensions.pChart.yiipImage');

 
 $Notas= DefaultController::getNotasCreditoEstatus();
  

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
 $Settings = array("R"=>255, "G"=>255, "B"=>255, "Dash"=>1, "DashR"=>255, "DashG"=>255, "DashB"=>255);
 $myPicture->drawFilledRectangle(0,0,1080,432,$Settings);

 /* Draw a gradient overlay */
 $Settings = array("StartR"=>1, "StartG"=>1, "StartB"=>1, "EndR"=>150, "EndG"=>150, "EndB"=>150, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,1080,432,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,1080,60,DIRECTION_VERTICAL,array("StartR"=>11,"StartG"=>198,"StartB"=>83,"EndR"=>80,"EndG"=>80,"EndB"=>80,"Alpha"=>100));

 /* Agregar borde a la imagen */
 $myPicture->drawRectangle(0,0,1079,429,array("R"=>11,"G"=>198,"B"=>83));

 /* Write the picture title */  
 //Descomentar
 $myPicture->setFontProperties(array("FontName"=>dirname(__FILE__).DIRECTORY_SEPARATOR."../../../../extensions/pChart/fonts/Forgotte.ttf","FontSize"=>22));
  

 $myPicture->drawText(10,35,"Notas aprobadas vs rechazadas",array("R"=>255,"G"=>255,"B"=>255));


 /* Set the default font properties */ 
 //Descomentar
 $myPicture->setFontProperties(array("FontName"=>dirname(__FILE__).DIRECTORY_SEPARATOR."../../../../extensions/pChart/fonts/Forgotte.ttf","FontSize"=>12,"R"=>0,"G"=>0,"B"=>0));


 /* Enable shadow computing */ 
 $myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>50));

 /* Create the pPie object */ 
 $PieChart = new yiipPie($myPicture,$MyData);

 /* Draw a simple pie chart */ 
 //$PieChart->draw2DPie(220,225,array("Radius"=>140,"SecondPass"=
 /* Draw an AA pie chart */ 
 $PieChart->draw2DPie(320,235,array("Radius"=>120,"DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE));

 /* Draw a splitted pie chart */ 
 $PieChart->draw2DPie(880,235,array("Radius"=>120,"WriteValues"=>PIE_VALUE_PERCENTAGE,"DataGapAngle"=>8,"DataGapRadius"=>6,"Border"=>TRUE,"BorderR"=>0,"BorderG"=>0,"BorderB"=>255));

  

 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
 
 //Descomentar
 $myPicture->setFontProperties(array("FontName"=>dirname(__FILE__).DIRECTORY_SEPARATOR."../../../../extensions/pChart/fonts/Forgotte.ttf","FontSize"=>14,"R"=>0,"G"=>0,"B"=>0)); 
 

 $PieChart->drawPieLegend(680,15,array("Style"=>LEGEND_ROUND,"Mode"=>LEGEND_VERTICAL));

 /* Render the picture (choose the best way) */
 //$myPicture->autoOutput("../../../themes/v1/pChart/fonts/Interfaz05.png");

 //$myPicture->render('themes/v2/images/aprobadasvsrechazadas.png');

echo base64_encode($myPicture->toBase64());
 
 ?>