<div class="contenttitle2">
	<h3>Actualizaci&oacute;n de Lotes</h3>
</div>
<div ng-app="calculate" class="logistica">
	<div id="outer" ng-controller="ctrlCalcular">
		<table class="stdtable evtProductsTable">
			<thead>
				<tr>
					<th class="head1" style="width:15%; text-align:center">C&oacute;digo</th>
					<th class="head1" style="width:35%; text-align:center">Descripci&oacute;n</th>
					<th class="head1" style="width:5%; text-align:center">Cantidad</th>
					<th class="head1" style="width:10%; text-align:center">% Acept.</th>
					<th class="head1" style="width:5%; text-align:center">No.de Lote</th>
					<th class="head1" style="width:5%; text-align:center">Almac&eacute;n</th>
					<th class="head1" style="width:5%; text-align:center">Caducidad</th>
					<th class="head1" style="width:10%; text-align:center">Total</th>
					<th class="head1" style="width:10%; text-align:center">I.V.A</th>					
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="item in items">
					<td>{{item.codigo}}</td>
					<td>{{item.descripcion}}</td>
					<td style="text-align:center">{{item.cantidad}}</td>
					<td style="text-align:center">{{item.aceptacion}}</td>
					<td style="text-align:right"><input ng-model="item.lote" data-id="{{item.id}}" class="evtChange input-small txtlote_{{item.id}}" type="text" /></td>
					<td style="text-align:right"><input ng-model="item.almacen" data-id="{{item.id}}" class="evtChange input-small txtalmacen_{{item.id}}" type="text" maxlength="2" /></td>
					<td style="text-align:right"><input ng-model="item.caducidad" data-id="{{item.id}}" jqdatepicker class="input-small txtcaducidad_{{item.id}}" type="text" /></td>
					<td style="text-align:right">{{ ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)| currency:"$ "}}</td>
					<td style="text-align:right">
						<span ng-show="{{(!item.no_iva)}}">
							{{ (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100)| currency:"$ "}}
						</span>
						<span ng-show="{{(item.no_iva)}}">
							{{ 0| currency:"$ "}}
						</span>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td style="text-align:right; font-weight:bold;" colspan="8">
						<h4>Total</h4>
					</td>
					<td style="text-align: right;">
						<h4>{{getTotal() | currency:"$ "}}</h4>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; font-weight:bold;" colspan="8">
						<h4>IVA</h4>
					</td>
					<td style="text-align: right;">
						<h4>{{getTotalIVA() | currency:"$ "}}</h4>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; font-weight:bold;" colspan="8">
						<h4>Gran Total</h4>
					</td>
					<td style="text-align: right;">
						<h4>{{getGranTotal() | currency:"$ "}}</h4>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
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
		        total += ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100);
		    }
		    return parseFloat(total).toFixed(2);
		};

		\$scope.getTotalIVA = function(){
		    var total = 0;
		    for(var i = 0; i < \$scope.items.length; i++){
		        var item = \$scope.items[i];
		        if (!item.no_iva) {
		        	total += (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100);
		        }
		    }
		    return parseFloat(total).toFixed(2);
		};

		\$scope.getGranTotal = function(){
		    var total = 0;
		    var iva = 0;
		    var importe = 0;
		    for(var i = 0; i < \$scope.items.length; i++){
		        var item = \$scope.items[i];
		        iva = 0;
		        importe = 0;
		        if (!item.no_iva) {
		        	iva = (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100);
		        }
		        importe = ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100);
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