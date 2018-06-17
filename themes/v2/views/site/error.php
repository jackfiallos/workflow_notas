<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="pageheader notab">
    <h1 class="pagetitle">Error <?php echo $code; ?></h1>
</div>
<div id="contentwrapper" class="contentwrapper">
	<?php echo $message; ?>
</div>