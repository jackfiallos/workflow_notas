<div ng-app="calculate">
	<div class="contenttitle2">
		<h3>Descripci&oacute;n de la Factura</h3>
	</div>
	<div ng-controller="ctrlCalcular">
		<div class="pure-g" style="margin-bottom:20px">
			<div class="pure-u-1-3">
				<h4 style="font-weight: bold;">Folio de Facturaci&oacute;n</h4>
				<h5>{{items.folio}}</h5>
			</div>
			<div class="pure-u-1-3">
				<h4 style="font-weight: bold;">Nombre del Cliente</h4>
				<h5> {{items.cliente}} </h5>
			</div>
			<div class="pure-u-1-3" style="text-align:right">
				<h4 style="font-weight: bold;">Fecha de Generaci&oacute;n</h4>
				<h5>{{items.fecha}}</h5>
			</div>
		</div>
		<div class="pure-g" style="margin-bottom:20px">
			<div class="pure-u-1-3">
				<h4 style="font-weight: bold;">Orden de Compra</h4>
				<h5> {{items.orden_compra}} </h5>
			</div>
			<div class="pure-u-2-3" style="text-align:right">
				<h4 style="font-weight: bold;">Importe Facturado</h4>
				<h5>{{items.monto| currency}}</h5>
			</div>
		</div>
		
		<div class="">
			<table class="stdtable evtProductsTable">
				<thead>
					<tr>
						<th class="head1" style="width:15%; text-align:center">C&oacute;digo</th>
						<th class="head1" style="width:30%; text-align:center">Descripci&oacute;n</th>
						<th class="head1" style="width:10%">Cantidad</th>
						<th class="head1" style="width:15%; text-align:center">Precio Unitario</th>
						<th class="head1" style="width:15%; text-align:center">Importe</th>
						<th class="head1" style="width:15%; text-align:center">I.V.A</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in items.productos">
						<td>{{item.codigo}}</td>
						<td>{{item.descripcion}}</td>
						<td style="text-align:center">{{item.cantidad}}</td>
						<td style="text-align:right">{{item.precio| currency}}</td>
						<td style="text-align:right">{{(item.precio * item.cantidad)| currency}}</td>
						<td style="text-align:right">{{item.costo_iva - (item.precio * item.cantidad)| currency}}</td>
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
							<h4>{{getIVATotal() | currency}}</h4>
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
</div>
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.min.js',CClientScript::POS_BEGIN);
if ($update) {
	$cs->registerScript('solicitudes.jquery.main', "
		var app = angular.module('calculate', []);

		app.controller('ctrlCalcular', function(\$scope) {
			\$scope.items = ".$jsonData.";
			\$scope.total = 0;
			\$scope.ivatotal = 0;

			\$scope.getTotal = function() {
			    \$scope.total = 0;
			    for(var i = 0; i < \$scope.items.productos.length; i++) {
			        var item = \$scope.items.productos[i];
			        \$scope.total += parseFloat(item.precio * item.cantidad);
			    }
			    return parseFloat(\$scope.total).toFixed(2);
			}

			\$scope.getIVATotal = function() {
			    \$scope.ivatotal = 0;
			    for(var i = 0; i < \$scope.items.productos.length; i++) {
			        var item = \$scope.items.productos[i];
			        \$scope.ivatotal += parseFloat(parseFloat(item.costo_iva).toFixed(2) - parseFloat(item.precio * item.cantidad).toFixed(2));
			    }
			    return parseFloat(\$scope.ivatotal).toFixed(2);
			}

			\$scope.getGranTotal = function() {
			    return parseFloat(\$scope.total + \$scope.ivatotal).toFixed(2);
			}
		});
	", CClientScript::POS_END);
}
?>