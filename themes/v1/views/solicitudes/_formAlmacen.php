<fieldset ng-app="calculate">
	<legend>Selecci&oacute;n de Productos</legend>
	<?php if (Yii::app()->user->getState('empresa_id') == 1): // Dermo ?>
		<div class="row-fluid" style="text-align:center">
			<div class="span6">
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-aderma.jpg" alt="" />
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-avene.jpg" alt="" />
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-ducray.jpg" alt="" />
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-elancyl.jpg" alt="" />
			</div>
			<div class="span6">
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-galenic.jpg" alt="" />
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-klorane.jpg" alt="" />
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-pfd.jpg" alt="" />
				<img style="padding:0 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/logo-pfd-sante.jpg" alt="" />
			</div>
		</div>
		<hr />
	<?php endif; ?>
	<div id="outer" ng-controller="ctrlCalcular">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group">
					<?php echo $form->labelEx($model,'product_id', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->dropDownList($model, 'product_id', $productos, array('class'=>'span12 chosen-select', 'empty'=>'Selecciona un Producto', 'data-placeholder'=>'Selecciona un Producto')); ?>
					</div>
				</div>
			</div>
			<div class="span4" style="padding-top:30px;">
				<a href="#" title="Agregar Producto" ng-click="evtAddProduct($event)" class="btn btn-inverse btn-small evtAddProduct"><i class="icon-plus"></i> Agregar Producto</a>
			</div>
		</div>
		<div class="row-fluid">
			<table class="table evtProductsTable">
				<thead>
					<tr>
						<th style="width:5%">#</th>
						<th style="width:10%; text-align:center">C&oacute;digo</th>
						<th style="width:30%; text-align:center">Descripci&oacute;n</th>
						<th style="width:5%; text-align:center">Cantidad</th>
						<th style="width:10%; text-align:center">Precio Unitario</th>
						<th style="width:10%; text-align:center">Importe</th>
						<th style="width:10%; text-align:center">% Aceptaci&oacute;n</th>
						<th style="width:10%; text-align:center">Total</th>						
						<th style="width:10%; text-align:center">I.V.A</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="item in items">
						<td><a class="btn btn-danger btn-small" ng-click="evtRemoveProduct(this, $event)" href="#" data-id="{{item.id}}" title="Eliminar"><i class="icon-trash"></i></a></td>
						<td>{{item.codigo}}</td>
						<td>{{item.descripcion}}</td>
						<td style="text-align:right">
							<input ng-model="item.cantidad" numericbinding min="1" data-id="{{item.id}}" class="evtChange input-small numeric" type="number" style="width:50px" />
						</td>
						<td style="text-align:right">{{ item.precio - ((item.descuento/100) * item.precio)| currency}}</td>
						<td style="text-align:right">{{ (item.precio - ((item.descuento/100) * item.precio)) * item.cantidad| currency}}</td>
						<td style="text-align:center">
							<input ng-model="item.aceptacion" numericbinding step="1" min="0" max="100" class="input-small numeric" type="number" style="width:50px" />
						</td>
						<td style="text-align:right">{{ ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)| currency}}</td>
						<td style="text-align:right">{{ (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100)| currency}}</td>
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
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.min.js',CClientScript::POS_BEGIN);
if ($update) {
	$cs->registerScript('solicitudes.jquery.main.angular', "
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
	                   scope.model = parseInt(scope.model);
	               }                  
	            }
	        };
		});

		app.controller('ctrlCalcular', function(\$scope) {
			\$scope.items = ".$jsonData.";

			\$scope.completarFactura = function(id) {
				jQuery.ajax({
					url: '".CController::createUrl('solicitudes/ObtenerListaProductos', array('id'=>$id))."',
					type: 'POST',
					data: jQuery('#solicitudes-form').serialize(),
					beforeSend: function(){
						$.blockUI({
							draggable: false,
							overlayCSS: { backgroundColor: '#000' },
							css: { width: '275px' },
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Obteniendo productos...</h4>'
						});
					},
					complete: function(){
						setTimeout($.unblockUI, 500);
					},
					success: function(data) {
						if (data[0].response) {
							\$scope.items.length = 0;
							for(row in data) {
								\$scope.items.push(data[row]);
								\$scope.\$apply();
							}
						}
						else {
							alert('No se pudieron importar los productos de la factura');
						}
					}
				});
			};

			\$scope.getTotal = function(){
			    var total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        //total += (item.precio - ((item.descuento/100) * item.precio)) * item.cantidad;
			        total += ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100);
			    }
			    return parseFloat(total).toFixed(2);
			};

			\$scope.getTotalIVA = function(){
			    var total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        //total += ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.impuesto/100);
			        total += (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100);
			    }
			    return parseFloat(total).toFixed(2);
			};

			\$scope.getGranTotal = function(){
			    var total = 0;
			    for(var i = 0; i < \$scope.items.length; i++){
			        var item = \$scope.items[i];
			        //iva = ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.impuesto/100);
			        //importe = ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad);
			        iva = (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100);
			        importe = ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100);
			        total += (iva + importe);
			    }
			    return parseFloat(total).toFixed(2);
			};

			\$scope.evtAddProduct = function(\$event) {
				\$event.preventDefault();
				jQuery.ajax({
					url: '".CController::createUrl('solicitudes/GuardarProductos', array('id'=>$id))."',
					type: 'POST',
					data: jQuery('#solicitudes-form').serialize(),
					beforeSend: function(){
						$.blockUI({
							draggable: false,
							overlayCSS: { backgroundColor: '#000' },
							css: { width: '275px' },
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Agregando Producto...</h4>'
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

			\$scope.evtRemoveProduct = function(el, \$event) {
				\$event.preventDefault();
				var index = el.\$index;
				jQuery.ajax({
					url: '".CController::createUrl('solicitudes/EliminarProducto', array('id'=>$id))."',
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
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Eliminando Producto...</h4>'
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
		var element = '';
		$(document).on('focus', '.evtChange', function(e) {
			element = $(this).val();
		}).on('blur', '.evtChange', function(e) {
			e.preventDefault();
			var el = $(this);
			if (element != el.val())
			{
				$.ajax({
					url: '".CController::createUrl('solicitudes/ActualizaCantidadProducto', array('id'=>$id))."',
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
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Actualizando Cantidad...</h4>'
						});
					},
					complete: function(){
						setTimeout($.unblockUI, 500);
					},
					success: function(data) {
						if (!data) {
							
						}
					}
				});
			}
		});
	", CClientScript::POS_READY);
}
?>