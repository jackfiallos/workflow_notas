<fieldset ng-app="calculate">
	<legend>Actualizaci&oacute;n de Lotes</legend>
	<div id="outer" ng-controller="ctrlCalcular">
		<div class="row-fluid">
			<table class="table evtProductsTable">
				<thead>
					<tr>
						<th style="width:15%; text-align:center">C&oacute;digo</th>
						<th style="width:25%; text-align:center">Descripci&oacute;n</th>
						<th style="width:5%; text-align:center">No.de Lote</th>
						<th style="width:5%; text-align:center">Almac&eacute;n</th>
						<th style="width:5%; text-align:center">Caducidad</th>
						<th style="width:5%; text-align:center">Cantidad</th>
						<th style="width:10%; text-align:center">Precio Unitario</th>
						<th style="width:10%; text-align:center">I.V.A</th>
						<th style="width:10%; text-align:center">Costo</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in items">
						<td>{{item.codigo}}</td>
						<td>{{item.descripcion}}</td>
						<td style="text-align:right"><input ng-model="item.lote" data-id="{{item.id}}" class="evtChange input-small txtlote_{{item.id}}" type="text" /></td>
						<td style="text-align:right"><input ng-model="item.almacen" data-id="{{item.id}}" class="evtChange input-small txtalmacen_{{item.id}}" type="text" maxlength="2" /></td>
						<td style="text-align:right"><input ng-model="item.caducidad" data-id="{{item.id}}" jqdatepicker class="input-small txtcaducidad_{{item.id}}" type="text" /></td>
						<td style="text-align:right">{{item.cantidad}}</td>
						<td style="text-align:right">{{ item.precio - ((item.descuento/100) * item.precio)| currency}}</td>
						<td style="text-align:right">{{ ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.impuesto/100)| currency}}</td>
						<td style="text-align:right">{{ (item.precio - ((item.descuento/100) * item.precio)) * item.cantidad| currency}}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="8">
							<h4>Total</h4>
						</td>
						<td style="text-align: right;">
							<h4>{{getTotal() | currency}}</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="8">
							<h4>IVA</h4>
						</td>
						<td style="text-align: right;">
							<h4>{{getTotalIVA() | currency}}</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="8">
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
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.datepicker-es.js',CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.min.js',CClientScript::POS_BEGIN);
$cs->registerScript('solicitudes.jquery.main.angular', "
	var app = angular.module('calculate', []);
	app.directive('numericbinding', function () {
        return {
            restrict: 'A',
            require: 'ngModel',
            scope: {
                model: '=ngModel',
            }
        };
	});

	app.directive('jqdatepicker', function() {
	    return {
	        restrict: 'A',
	        require: 'ngModel',
	        link: function(scope, element, attrs, ngModelCtrl) {
	            $(element).datepicker({
	                dateFormat: 'yy-mm-dd',
					showButtonPanel: true,
					minDate: 0,
					changeMonth: true,
					changeYear: true,
	                onSelect: function(date, inst) {
	                    var ngModelName = this.attributes['ng-model'].value;
	                    scope[ngModelName] = date;
	                    scope.\$apply();
	                    var _id = $(inst.input[0]).attr('data-id');
						$.ajax({
							url: '".CController::createUrl('logistica/ActualizaLoteProducto', array('id'=>$id))."',
							type: 'POST',
							data: {
								YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
								id: _id,
								lote: $('.txtlote_'+_id).val(),
								almacen: $('.txtalmacen_'+_id).val(),
								caducidad: $('.txtcaducidad_'+_id).val(),
							},
							success: function(data) {
								if (!data) {}
							}
						});
	                }

	            });
	        }
	    };
	});

	app.controller('ctrlCalcular', function(\$scope) {
		\$scope.items = ".$jsonData.";

		\$scope.getTotal = function(){
			    var total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        total += (item.precio - ((item.descuento/100) * item.precio)) * item.cantidad;
			    }
			    return parseFloat(total).toFixed(2);
			};

			\$scope.getTotalIVA = function(){
			    var total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        total += ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.impuesto/100);
			    }
			    return parseFloat(total).toFixed(2);
			};

			\$scope.getGranTotal = function(){
			    var total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        iva = ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.impuesto/100);
			        importe = ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad);
			        total += (iva + importe);
			    }
			    return parseFloat(total).toFixed(2);
			};
	});
", CClientScript::POS_END);

$cs->registerScript('solicitudes.jquery.main.form', "

	$('.txtdatepicker').datepicker({
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true,
		minDate: 0,
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateText, inst) {
			var _id = $(inst.input[0]).attr('data-id');
			$.ajax({
				url: '".CController::createUrl('logistica/ActualizaLoteProducto', array('id'=>$id))."',
				type: 'POST',
				data: {
					YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
					id: _id,
					lote: $('.txtlote_'+_id).val(),
					almacen: $('.txtalmacen_'+_id).val(),
					caducidad: $('.txtcaducidad_'+_id).val(),
				},
				success: function(data) {
					if (!data) {}
				}
			});
		}
	});

	$(document).on('blur', '.evtChange', function(e) {
		e.preventDefault();
		var el = $(this);
		$.ajax({
			url: '".CController::createUrl('logistica/ActualizaLoteProducto', array('id'=>$id))."',
			type: 'POST',
			data: {
				YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
				id: el.attr('data-id'),
				lote: $('.txtlote_'+el.attr('data-id')).val(),
				almacen: $('.txtalmacen_'+el.attr('data-id')).val(),
				caducidad: $('.txtcaducidad_'+el.attr('data-id')).val(),
			},
			success: function(data) {
				if (!data) {}
			}
		});
	});
", CClientScript::POS_READY);
?>