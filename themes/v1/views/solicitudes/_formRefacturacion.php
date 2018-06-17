<fieldset ng-app="calculate">
	<legend>Descripci&oacute;n de la Factura</legend>
	<div ng-controller="ctrlCalcular">
		<div class="row-fluid">
			<div class="span4">
				<h4 style="font-weight: bold;">Folio de Facturaci&oacute;n</h4>
				<h5>{{items.folio}}</h5>
			</div>
			<div class="span4">
				<h4 style="font-weight: bold;">Nombre del Cliente</h4>
				<h5> {{items.cliente}} </h5>
			</div>
			<div class="span4" style="text-align:right">
				<h4 style="font-weight: bold;">Fecha de Generaci&oacute;n</h4>
				<h5>{{items.fecha}}</h5>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<h4 style="font-weight: bold;">Orden de Compra</h4>
				<h5> {{items.orden_compra}} </h5>
			</div>
			<div class="offset4 span4" style="text-align:right">
				<h4 style="font-weight: bold;">Importe Facturado</h4>
				<h5>{{items.monto| currency}}</h5>
			</div>
		</div>
		<hr />
		<div class="row-fluid">
			<table class="table evtProductsTable">
				<thead>
					<tr>
						<th style="width:15%; text-align:center">C&oacute;digo</th>
						<th style="width:45%; text-align:center">Descripci&oacute;n</th>
						<th style="width:10%">Cantidad</th>
						<th style="width:15%; text-align:center">Precio Unitario</th>
						<th style="width:15%; text-align:center">I.V.A</th>
						<th style="width:15%; text-align:center">Importe</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in items.productos">
						<td>{{item.codigo}}</td>
						<td>{{item.descripcion}}</td>
						<td>{{item.cantidad}}</td>
						<td style="text-align:right">{{item.precio| currency}}</td>
						<td style="text-align:right">{{item.precio| currency}}</td>
						<td style="text-align:right">{{item.costo_iva| currency}}</td>
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
							<h4>{{getTotal() | currency}}</h4>
						</td>
					</tr>
					<tr>
						<td style="text-align:right; font-weight:bold;" colspan="5">
							<h4>Gran Total</h4>
						</td>
						<td style="text-align: right;">
							<h4>{{getTotal() | currency}}</h4>
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

		app.controller('ctrlCalcular', function(\$scope) {
			\$scope.items = ".$jsonData.";
			\$scope.total = 0;

			\$scope.getTotal = function() {
			    \$scope.total = 0;
			    for(var i = 0; i < \$scope.items.productos.length; i++) {
			        var item = \$scope.items.productos[i];
			        \$scope.total += parseFloat(item.costo_iva);
			    }
			    return parseFloat(\$scope.total).toFixed(2);
			}
		});
	", CClientScript::POS_END);
}
?>