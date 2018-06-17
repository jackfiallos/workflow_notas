<div ng-app="calculate">
	<div class="contenttitle2">
		<h3>Selecci&oacute;n de Marcas</h3>
	</div>
	<div ng-controller="ctrlCalcular" >
		<div class="pure-g" style="margin-bottom:20px">
			<div class="pure-u-2-5">
				<div class="control-group">
					<?php echo $form->labelEx($model,'marca_id', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->dropDownList($model, 'marca_id', $marcas, array('class'=>'span12 chosen-select', 'empty'=>'Selecciona una Marca', 'data-placeholder'=>'Selecciona una Marca')); ?>
					</div>
				</div>
			</div>
			<div class="pure-u-1-3" style="padding-top:30px;">
				<a href="#" title="Agregar Marca" ng-click="evtAddBrand($event)" class="button-large pure-button button-success evtAddBrand"><i class="fa fa-plus"></i> Agregar Marca</a>
			</div>
		</div>
		<div class="">
			<table class="stdtable evtProductsTable">
				<thead>
					<tr>
						<th class="head1" style="width:10%">#</th>
						<th class="head1" style="width:15%; text-align:center">C&oacute;digo</th>
						<th class="head1" style="width:15%; text-align:center">Marca</th>
						<th class="head1" style="width:15%; text-align:center">Descuento (%)</th>
						<th class="head1" style="width:15%; text-align:center">I.V.A</th>
						<th class="head1" style="width:15%; text-align:center">Importe</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in items">
						<td><a style="padding:5px" class="btn btn-danger btn-small" ng-click="evtRemoveBrand(this, $event)" href="#" data-id="{{item.id}}" title="Eliminar"><i class="fa fa-trash-o"></i></a></td>
						<td>{{item.codigo}}</td>
						<td>{{item.marca}}</td>
						<td style="text-align:center">
							<div class="input-append">
								<input ng-model="item.importe" ng-change="change($index, this, $event)" numericbinding step="0.01" min="0" max="{{getAvailableDiscount()}}" data-id="{{item.id}}" class="evtChange input-small numeric disallownumeric" type="number" />
  								<span class="add-on"><i class="fa fa-check"></i></span>
							</div>							
						</td>
						<td style="text-align:right">{{(((item.importe*item.monto)/100)*(<?php echo Yii::app()->params['iva']; ?>/100))| currency}}</td>
						<td style="text-align:right">{{((item.importe*item.monto)/100)| currency}}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="5">
							<h4>Total</h4>
						</td>
						<td style="text-align: right;">
							<h4>{{getTotal() | currency}}</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="5">
							<h4>I.V.A</h4>
						</td>
						<td style="text-align: right;">
							<h4>{{getTotalIVA() | currency}}</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="5">
							<h4>Gran Total</h4>
						</td>
						<td style="text-align: right;">
							<h4>{{getGranTotal() | currency}}</h4>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</fieldset>
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.min.js',CClientScript::POS_BEGIN);
if ($update) {
	$cs->registerScript('solicitudes.jquery.main', "
		var app = angular.module('calculate', []);

		app.directive('numericbinding', function () {
	        return {
	            restrict: 'A',
	            require: 'ngModel',
	            scope: {
	                model: '=ngModel',
	            },                
	           link: function (scope, element, attrs, ngModelCtrl) {
	               if (scope.model && typeof scope.model == 'string') {
	                   scope.model = parseFloat(scope.model);
	               }                  
	            }
	        };
		});

		app.controller('ctrlCalcular', function(\$scope) {
			\$scope.items = ".$jsonData.";
			\$scope.importe_definido = ".(!empty($model->importe) ? $model->importe : 0).";
			\$scope.total = 0;
			\$scope.percent_available = 100;
			\$scope.total_importes = 0;

			\$scope.initialize = function(){
				for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        \$scope.total_importes += parseFloat(item.importe).toFixed(2);
			    }
				\$scope.percent_available = 100 - \$scope.total_importes;
			};

			\$scope.change = function(index, el, \$event){
				\$scope.total_importes = 0;
				for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        \$scope.total_importes += item.importe;
			    }
				//\$scope.percent_available = parseFloat(100 - \$scope.total_importes + \$scope.items[index].importe).toFixed(2);
				\$scope.percent_available = 100;
			};

			\$scope.getAvailableDiscount = function(){
				return \$scope.percent_available;
			};

			\$scope.getTotal = function(){
			    \$scope.total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        \$scope.total += ((item.importe*item.monto)/100);
			    }
			    return parseFloat(\$scope.total).toFixed(2);
			};

			\$scope.getTotalIVA = function(){
			    \$scope.total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        \$scope.total += (((item.importe*item.monto)/100) * (".Yii::app()->params['iva']."/100));
			    }
			    return (parseFloat(\$scope.total).toFixed(2));
			};

			\$scope.getGranTotal = function(){
			    \$scope.total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        \$scope.total += (((item.importe*item.monto)/100) * (".Yii::app()->params['iva']."/100)) + ((item.importe*item.monto)/100);
			    }
			    return (parseFloat(\$scope.total).toFixed(2));
			};

			\$scope.evtAddBrand = function(\$event) {
				\$event.preventDefault();
				jQuery.ajax({
					url: '".CController::createUrl('solicitudes/GuardarDescuentos', array('id'=>$id))."',
					type: 'POST',
					data: jQuery('#solicitudes-form').serialize(),
					beforeSend: function(){
						$.blockUI({
							draggable: false,
							overlayCSS: { backgroundColor: '#000' },
							css: { width: '275px' },
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Agregando Descuento...</h4>'
						});
					},
					complete: function(){
						setTimeout($.unblockUI, 500);
					},
					success: function(data) {
						if (data.response) {
							\$scope.items.push(data);
							\$scope.\$apply();
						}
					}
				});
			};

			\$scope.evtRemoveBrand = function(el, \$event) {
				\$event.preventDefault();
				var index = el.\$index;
				jQuery.ajax({
					url: '".CController::createUrl('solicitudes/EliminarDescuento', array('id'=>$id))."',
					type: 'POST',
					data: {
						YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
						id: el.item.id
					},
					beforeSend: function(){
						$.blockUI({
							draggable: false,
							overlayCSS: { backgroundColor: '#000' },
							css: { width: '275px' },
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Eliminando Descuento...</h4>'
						});
					},
					complete: function(){
						setTimeout($.unblockUI, 500);
					},
					success: function(data) {
						if (data) {
							\$scope.items.splice(index, 1);
							\$scope.\$apply();
						}
					}
				});
			};
		});
	", CClientScript::POS_END);

	$cs->registerScript('solicitudes.jquery.main.form', "
		$(document).on('blur', '.evtChange', function(e) {
			e.preventDefault();
			var el = $(this);
			$.ajax({
				url: '".CController::createUrl('solicitudes/ActualizaImporteMarcas', array('id'=>$id))."',
				type: 'POST',
				data: {
					YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
					id: el.attr('data-id'),
					value: el.val()
				},
				beforeSend: function(){
						$.blockUI({
							draggable: false,
							overlayCSS: { backgroundColor: '#000' },
							css: { width: '275px' },
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Actualizando Descuento...</h4>'
						});
					},
					complete: function(){
						setTimeout($.unblockUI, 500);
					},
				success: function(data) {
					if (!data) {
						el.next().addClass('add-on-error');
						el.next().children('i').removeClass('fa-check').addClass('fa-exclamation-triangle');
					}
					else {
						el.next().removeClass('add-on-error');
						el.next().children('i').removeClass('fa-exclamation-triangle').addClass('fa-check');
					}
				}
			});
		});
	", CClientScript::POS_READY);
}
?>