<?php   
 /* CAT:Pie charts */

 /* pChart library inclusions */

  

Yii::import('application.extensions.pChart.yiipData');
Yii::import('application.extensions.pChart.yiipDraw');
Yii::import('application.extensions.pChart.yiipPie');
Yii::import('application.extensions.pChart.yiipImage');

 
 $Notas= DefaultController::getNotasCreditoTiempoPromedio();
  
 /* Create and populate the pData object */
 $MyData = new yiipData();   
 
 /* Create the pChart object */
 $myPicture = new yiipImage(1080,50,$MyData);

 $myPicture->drawGradientArea(0,0,1080,50,DIRECTION_VERTICAL,array("StartR"=>191,"StartG"=>189,"StartB"=>34,"EndR"=>80,"EndG"=>80,"EndB"=>80,"Alpha"=>100));

 /* Agregar borde a la imagen */
 $myPicture->drawRectangle(0,0,1199,50,array("R"=>191,"G"=>189,"B"=>34));

 /* Write the picture title */  
 //Descomentar
 $myPicture->setFontProperties(array("FontName"=>dirname(__FILE__).DIRECTORY_SEPARATOR."../../../../extensions/pChart/fonts/Forgotte.ttf","FontSize"=>22));
  

 $myPicture->drawText(10,35,"Tiempo promedio que les toma a las notas desde su generacion hasta su cierre [".$Notas['valores']."] días",array("R"=>255,"G"=>255,"B"=>255));
 
 //$myPicture->render('themes/v2/images/tiempopromedio.png');

echo base64_encode($myPicture->toBase64());
 
 ?>