<form action="" class="pure-form pure-form-stacked">
   <div ng-app="calculate">
      <div ng-controller="ctrlCalcular">
         <ul class="hornav">
            <li ng-repeat="item in items.marcas" class="{{ (item.current) ? 'current' : '' }}">
               <a href="#{{item.url}}">{{item.marca}}</a>
            </li>
            <li>
               <a href="#ptotal">Total</a>
            </li>
         </ul>

         <div class="contentwrapper">
            <div class="content" ng-repeat="item in items.marcas">
               <div id="{{item.url}}" class="subcontent" style="{{ (!item.current) ? 'display:none' : '' }}">
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
                     <!--<tbody>
                        <tr>
                           <td colspan="8">
                              Filtro de B&uacute;squeda R&aacute;pida por Productos: <input type="search" ng-model="search.$" placeholder="Filtrar">
                           </td>
                        </tr>
                     </tbody>-->
                     <tbody ng-repeat="linea in item.lineas">
                        <tr ng-show="(filter:search).length > 0">
                           <td colspan="8" style="color:{{item.color}}; background-color: {{item.backgroundcolor}}; text-align:center; font-weight:bold;">{{linea.titulo}}</td>
                        </tr>
                        <tr ng-repeat="producto in linea.productos | filter:search">
                           <td>{{producto.codigo}}</td>
                           <td>{{producto.descripcion}}</td>
                           <td style="text-align:center">
                              <input ng-model="producto.cantidad" numericbinding min="0" data-id="{{producto.id}}" class="evtChange input-small numeric" type="number" style="width:80px" />
                           </td>
                           <td style="text-align:right">{{ producto.precio - ((producto.descuento/100) * producto.precio)| currency}}</td>
                           <td style="text-align:right">{{ (producto.precio - ((producto.descuento/100) * producto.precio)) * producto.cantidad| currency}}</td>
                           <td style="text-align:center">
                              <input ng-model="producto.aceptacion" numericbinding min="1" max="100" data-id="{{producto.id}}" class="evtChangeAceptacion input-small numeric" type="number" style="width:80px" />
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
            <div class="content" ng-repeat="item in items.marcas">
               <div id="ptotal" class="subcontent" style="display:none">
                  
               </div>
            </div>
         </div>
      </div>
   </div>
</form>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.min.js', CClientScript::POS_BEGIN);
$cs->registerScript('solicitudes.jquery.main.angular', "
   var app = angular.module('calculate', []);
   app.controller('ctrlCalcular', function(\$scope) {
      \$scope.items = ".$marcas.";
      \$scope.test = function(\$event) {
         \$event.preventDefault();
         jQuery.ajax({
            url: '".CController::createUrl('site/Get')."',
            type: 'POST',
            data: {
               YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."',
               items: \$scope.items,  
            },
            success: function(data) {}
         });
      };
   });
", CClientScript::POS_END);
?>