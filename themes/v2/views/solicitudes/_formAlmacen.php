

<div ng-app="calculate">
	<div class="contenttitle2">
		<h3>Selecci&oacute;n de Productos</h3>
	</div>
	<div id="outer" ng-controller="ctrlCalcular">
		<div>
			<div ng-app="calculate">
		      	<div ng-controller="ctrlCalcular">
		      		<?php if (empty($model->factura_id)): ?>
		         	<ul class="hornav">
			            <li ng-repeat="item in mitems.marcas" class="{{ (item.current) ? 'current' : '' }}">
			               	<a href="#{{item.url}}">{{item.marca}}</a>
			            </li>
			            <li>
			               	<a href="#ptotal">Total</a>
			            </li>
		         	</ul>
					<?php endif; ?>
		         	<div class="contentwrapper">
		         		<?php if (empty($model->factura_id)): ?>
			            <div class="content marca" ng-repeat="item in mitems.marcas" data-marca="{{$index}}">
			               	<div id="{{item.url}}" class="subcontent" ng-style="{'display':((!item.current) ? 'none' : '')}">
			                  <div class="decorationline">
			                     <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logos/{{item.logo}}" alt="{{item.marca}}" />
			                  </div>
			                  <table class="stdtable evtProductsTable" style="margin-top:20px;">
			                     <thead>
			                        <tr>
			                           <th class="head1" style="width:10%; text-align:center">C&Oacute;DIGO</th>
			                           <th class="head1" style="wilogo-avene.jpgdth:25%; text-align:center">DESCRIPCI&Oacute;N</th>
			                           <th class="head1" style="width:10%; text-align:center">CANTIDAD</th>
			                           <th class="head1" style="width:10%; text-align:center">P.U.</th>
			                           <th class="head1" style="width:10%; text-align:center">IMPORTE</th>
			                           <th class="head1" style="width:10%; text-align:center">% ACEPT.</th>
			                           <th class="head1" style="width:10%; text-align:center">TOTAL</th>
			                           <th class="head1" style="width:10%; text-align:center">I.V.A</th>
			                        </tr>
			                     </thead>
			                     <tbody class="linea" ng-repeat="linea in item.lineas" data-linea="{{$index}}">
			                        <tr>
			                           <td colspan="8" ng-style="{'color':item.color, 'background-color':item.backgroundcolor}" style="text-align:center; font-weight:bold;">{{linea.titulo}}</td>
			                        </tr>
			                        <tr class="producto" ng-repeat="producto in linea.productos | filter:search" data-index="{{$index}}">
			                           <td>{{producto.codigo}}</td>
			                           <td>{{producto.descripcion}}</td>
			                           <td style="text-align:center">
			                              <input ng-model="producto.cantidad" numericbinding min="0" data-id="{{producto.id}}" class="input-small numeric" type="number" style="width:80px" ng-focus="setValue($event)" ng-blur="actualizarVista($event, true)" />
			                           </td>
			                           <td style="text-align:right">{{ producto.precio - ((producto.descuento/100) * producto.precio)| currency}}</td>
			                           <td style="text-align:right">{{ (producto.precio - ((producto.descuento/100) * producto.precio)) * producto.cantidad| currency}}</td>
			                           <td style="text-align:center">
			                              <input ng-model="producto.aceptacion" numericbinding min="1" max="100" data-id="{{producto.id}}" class="input-small numeric" type="number" style="width:80px" ng-focus="setValue($event)" ng-blur="actualizarVista($event, false)" />
			                           </td>
			                           <td style="text-align:right">{{ ((producto.precio - ((producto.descuento/100) * producto.precio)) * producto.cantidad) * (producto.aceptacion/100)| currency}}</td>
			                           <td style="text-align:right">
			                              <span ng-show="{{(!producto.no_iva)}}">
			                                 {{ (((producto.precio - ((producto.descuento/100) * producto.precio)) * producto.cantidad) * (producto.aceptacion/100)) * (producto.impuesto/100)| currency}}
			                              </span>
			                              <span ng-show="{{(producto.no_iva)}}">
			                                 {{ 0| currency}}
			                              </span>
			                           </td>
			                        </tr>
			                     </tbody>
			                  </table>
			               	</div>
			            </div>
			        	<?php endif; ?>
			            <div class="content">
			            	<div id="ptotal" class="subcontent" style="display:<?php echo (!empty($model->factura_id) ? 'block' : 'none'); ?>">
			                	<table class="stdtable evtProductsTable">
									<thead>
										<tr>
											<th class="head1" style="width:5%; text-align:center">#</th>
											<th class="head1" style="width:10%; text-align:center">C&oacute;digo</th>
											<th class="head1" style="width:25%; text-align:center">Descripci&oacute;n</th>
											<th class="head1" style="width:10%; text-align:center">Cantidad</th>
											<th class="head1" style="width:10%; text-align:center">P.U.</th>
											<th class="head1" style="width:10%; text-align:center">Importe</th>
											<th class="head1" style="width:10%; text-align:center">% Aceptaci&oacute;n</th>
											<th class="head1" style="width:10%; text-align:center">Total</th>						
											<th class="head1" style="width:10%; text-align:center">I.V.A</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="item in items">
											<td><a style="padding:5px" class="btn btn-danger btn-small" ng-click="evtRemoveProduct(this, $event)" href="#" data-id="{{item.id}}" title="Eliminar"><i class="fa fa-trash-o"></i></a></td>
											<td>{{item.codigo}}</td>
											<td>{{item.descripcion}}</td>
											<!--<td style="text-align:center">{{item.cantidad}}</td>-->
											<td style="text-align:center">
			                              		<input ng-model="item.cantidad" numericbinding min="1" data-code="{{item.codigo}}" data-id="{{item.id}}" class="input-small numeric" type="number" style="width:80px" ng-focus="setValue($event)" ng-blur="actualizarProducto(this, $event)" />
			                           		</td>
											<td style="text-align:right">{{ item.precio - ((item.descuento/100) * item.precio)| currency}}</td>
											<td style="text-align:right">{{ (item.precio - ((item.descuento/100) * item.precio)) * item.cantidad| currency}}</td>
											<!--<td style="text-align:center">{{item.aceptacion}}</td>-->
											<td style="text-align:center">
			                              		<input ng-model="item.aceptacion" numericbinding min="1" max="100" data-code="{{item.codigo}}" data-id="{{item.id}}" class="input-small numeric" type="number" style="width:80px" ng-focus="setValue($event)" ng-blur="actualizarAceptacion(this, $event)" />
			                           		</td>
											<td style="text-align:right">{{ ((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)| currency}}</td>
											<td style="text-align:right">
												<span ng-show="{{(!item.no_iva)}}">
													{{ (((item.precio - ((item.descuento/100) * item.precio)) * item.cantidad) * (item.aceptacion/100)) * (item.impuesto/100)| currency}}
												</span>
												<span ng-show="{{(item.no_iva)}}">
													{{ 0| currency}}
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
		        	</div>
		      	</div>
		   	</div>
		</div>
	</div>
</div>
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
			\$scope.mitems = ".$jsonDataMarcas.";
			\$scope.element = '';

			\$scope.setValue = function(\$event){
				\$scope.element = jQuery(\$event.target).val();
			};

			\$scope.actualizarVista = function(\$event, esCantidad) {
				var el = jQuery(\$event.target);
				var aceptacion = 100;
				var cantidad = 0;

				if(esCantidad) {
					cantidad = el.val();
					aceptacion = el.parent().next().next().next().find('input').val();
				}
				else {
					aceptacion = el.val();
					cantidad = el.parent().prev().prev().prev().find('input').val();
				}

				if (\$scope.element != el.val())
				{
					jQuery.ajax({
						url: '".CController::createUrl('solicitudes/ActualizaProducto', array('id'=>$id))."',
						type: 'POST',
						data: {
							YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
							id: el.attr('data-id'),
							value: cantidad,
							aceptacion: aceptacion,
							anio: '".$model->anio."',
							cliente: '".$model->cliente."'
						},
						success: function(response) {
							\$scope.items.length = 0;
							\$scope.items = response;
							\$scope.\$apply();
						}
					});
					\$scope.element = el.val();
				}
			};

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
						window.location.href = '".Yii::app()->createUrl('solicitudes/update', array('id'=>$id))."';
					}
				});
			};

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
					success: function(data) {
						if (data) {
							// En el arreglo de marcas-lineas-productos encontrar el producto eliminado y resetearlo
							for(var marca in \$scope.mitems.marcas) {
								for(var linea in \$scope.mitems.marcas[marca].lineas) {
									for(var producto in \$scope.mitems.marcas[marca].lineas[linea].productos) {
										if (\$scope.mitems.marcas[marca].lineas[linea].productos[producto].codigo == \$scope.items[index].codigo) {
											\$scope.mitems.marcas[marca].lineas[linea].productos[producto].cantidad = 0;
											\$scope.mitems.marcas[marca].lineas[linea].productos[producto].aceptacion = 100;
										}
									}
								}
							}

							\$scope.items.splice(index, 1);
							\$scope.\$apply();
						}
					}
				});
			};

			\$scope.actualizarProducto = function(el, \$event){
				\$event.preventDefault();
				var el = jQuery(\$event.target);
				if (\$scope.element != el.val())
				{
					jQuery.ajax({
						url: '".CController::createUrl('solicitudes/ActualizaProducto', array('id'=>$id))."',
						type: 'POST',
						data: {
							YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
							id: el.attr('data-id'),
							value: el.val(),
							aceptacion: el.parent().next().next().next().find('input').val(),
							anio: '".$model->anio."',
							cliente: '".$model->cliente."'
						},
						success: function(data) {
							if (data) {
								// En el arreglo de marcas-lineas-productos encontrar el producto eliminado y resetearlo
								for(var marca in \$scope.mitems.marcas) {
									for(var linea in \$scope.mitems.marcas[marca].lineas) {
										for(var producto in \$scope.mitems.marcas[marca].lineas[linea].productos) {
											if (\$scope.mitems.marcas[marca].lineas[linea].productos[producto].codigo == el.attr('data-code')) {
												\$scope.mitems.marcas[marca].lineas[linea].productos[producto].cantidad = parseFloat(el.val()).toFixed(2);
											}
										}
									}
								}
								\$scope.\$apply();
							}
						}
					});
				}
			};

			\$scope.actualizarAceptacion = function(el, \$event){
				\$event.preventDefault();
				var el = jQuery(\$event.target);
				if (\$scope.element != el.val())
				{
					jQuery.ajax({
						url: '".CController::createUrl('solicitudes/ActualizaAceptacion', array('id'=>$id))."',
						type: 'POST',
						data: {
							YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
							id: el.attr('data-id'),
							value: el.val()
						},
						success: function(data) {
							for(var marca in \$scope.mitems.marcas) {
								for(var linea in \$scope.mitems.marcas[marca].lineas) {
									for(var producto in \$scope.mitems.marcas[marca].lineas[linea].productos) {
										if (\$scope.mitems.marcas[marca].lineas[linea].productos[producto].codigo == el.attr('data-code')) {
											\$scope.mitems.marcas[marca].lineas[linea].productos[producto].aceptacion = parseInt(el.val(), 10);
										}
									}
								}
							}
							\$scope.\$apply();
						}
					});
				}
			};
		});
	", CClientScript::POS_END);

	$cs->registerScript('solicitudes.jquery.main.form', "
		var element = '';
		var elementAceptacion = '';
		var factura_id = $('#".CHtml::activeId($model, 'factura_id')."').val();

		// Si se ha eliminado el num de factura, enviar al servidor la señal de que elimine los productos relacionados
		$('#".CHtml::activeId($model, 'factura_id')."').on('blur', function() {
			if ((factura_id.length > 0) && ($(this).val().length == 0)){
				// enviar la señal al servidor de que se eliminara la relacion con la factura y todos sus productos
				$.ajax({
					url: '".CController::createUrl('solicitudes/EliminaRelacionFactura', array('id'=>$id))."',
					type: 'POST',
					data: jQuery('#solicitudes-form').serialize(),
					beforeSend: function(){
						$.blockUI({
							draggable: false,
							overlayCSS: { backgroundColor: '#000' },
							css: { width: '275px' },
							message: '<h4><img src=\"".Yii::app()->theme->baseUrl."/css/loader.gif\" /> Actualizando la nota...</h4>'
						});
					},
					complete: function(){
						setTimeout($.unblockUI, 500);
					},
					success: function(data) {
						if (data[0] == true){
							var scope = angular.element($(\"#outer\")).scope();
			    			scope.\$apply(function() {
				        		scope.completarFactura(0);
				    		});
						}
					}
				});
			}
		});
	", CClientScript::POS_READY);
}
?>