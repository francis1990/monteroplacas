(function ($) {
    $(document).ready(function() {
        // $('.add-form-embedded').bind('click', AdicionarArticulo);
        var content = $('#content');
        content.on('click', '.remove-form-embedded', function (clickEvent) {
            clickEvent.preventDefault();

            var rowForm = $(this).closest('tr');
            var parent = rowForm.parent();

            if (rowForm.length) {
                rowForm.remove();
                // Lanzando un evento notificando que fue eliminado el formulario
                parent.trigger("embedded.form.deleted");
            } 
        });
        ThingsToDo();
        document.getElementById("nota_credito_venta").onchange=ThingsToDo;

    });

    function AdicionarArticulo(articuloVenta, i)
    {
        // clickEvent.preventDefault();
        var container = $('#articulonotacreditos-tbody');
        var form = container.data('prototype');
        var index = container.data('index');

        var rowForm = form.replace(/__name__/g, index);
        var htmlObject = $('<tr></tr>');
        htmlObject.append(rowForm);
        container.append(htmlObject);
        container.data('index', index + 1); // aumentando el indice
        // debugger
        htmlObject.find('label').remove();
        var inputElemsParent = htmlObject.children('div').children('div');
        htmlObject.find('div').remove();
        var collection = inputElemsParent.get();
        // $.each(collection, function (j) {
        //     htmlObject.append('<td>' + collection[j].innerHTML + '</td>');
        // });
        htmlObject.append('<td>' + collection[0].innerHTML+ '</td>' + //seccion
        '<td>' + collection[1].innerHTML + '</td>' +                  //articulo
        '<td>' + collection[2].innerHTML + '</td>' +                  //precio
        '<td>' + collection[3].innerHTML + '</td>' +                  //cantidad
        '<td>' + collection[4].innerHTML + '</td>' +                  //importe
        '<td>' + '<button type="button" title="Eliminar artículo" class="remove-form-embedded">' + 
            '<i class="glyphicon glyphicon-trash"></i></button>' + 
        '</td>');
        var thead = container.closest('thead');
        thead.find('tr').append('<td></td>');

        var a = container.find('tr:last').find('select:last').find('option').length;
        console.log("option = " + a);
        console.log(container.find('tr:last').find('select:last').find('option:first').html());
        var options = container.find('tr:last').find('select:last').find('option');
        options.each(function () {
            var articuloStr = $(this).html();
            if (articuloStr == articuloVenta.articulo) 
            {
                $(this).addClass('selected');                
            }
            else
            {
                $(this).remove();
            }
        });
        
        // options.eq(24).addClass('selected');
        // var d = container.find('tr:last').find('select:last').find('option[class]');
        // $.each(options, function(i){
        //     if(i != 24)
        //     {
        //         container.find('tr:last').find('select:last').find('option').remove();
        //     }
        // });
        // options = container.find('tr:last').find('select:last').find('option');

        container.find('tr:last').find('input').eq(0).val(articuloVenta.precio);
        container.find('tr:last').find('input').eq(1).val(articuloVenta.cantidad);
        // container.find('tr:last').find('input').eq(1).addClass('cantidad');
        container.find('tr:last').find('input').eq(2).val(articuloVenta.importe);

    }
    function ThingsToDo () {
        var venta = $('#nota_credito_venta option:selected');
            
            $('#venta-fecha').val(venta.data('ventaFecha'));
            $('#venta-cliente').val(venta.data('ventaCliente'));
            $('#venta-vendedor').val(venta.data('ventaVendedor'));
            $('#venta-centrodecosto').val(venta.data('ventaCentrodecosto'));
            $('#venta-formadepago').val(venta.data('ventaFormadepago'));
            $('#venta-observacion').val(venta.data('ventaObservacion'));

            var ventaId = venta.data('ventaId');
            $('#aceptarBtn').attr('href', "/monteroplacas/web/venta/show/" + ventaId);
            
            $.ajax({
                method  : "GET",
                url     : "/monteroplacas/web/notacredito/ajax",
                data    : { 'ventaId' : venta.data('ventaId')},
                dataType : 'json'
            })
            .done(function (data) { 
                var articulosVenta = JSON.parse(data.datos);
                console.log("CANTIDAD = " + articulosVenta);                                    

                $('#articulonotacreditos-tbody').children('tr').remove();
                // Agregar por cada articulo en articulosVenta una fila en la tabla de los articulos relacionados con la venta
                var cantArticulos = 0;
                var lim = articulosVenta.length;                    
                for (var i = 0; i < lim; i++) 
                {
                    var articuloVenta = articulosVenta[i];
                    AdicionarArticulo(articuloVenta, i);
                    cantArticulos += articuloVenta.cantidad;
                }

                
                // debugger
                $('#cantArticulos-input').val(cantArticulos);
                $('input[id$="cantidad"]', 'td').bind('keyup', function () {
                    ModificarValoresInformativos();
                });
                $('input[id$="precio"]', 'td').bind('keyup', function () {
                    ModificarValoresInformativos();
                });
                ModificarValoresInformativos();
                console.log("DONE");
            })
            .fail(function (jqXHR, exception) {
                alert("Falló");
                if (jqXHR.status === 405) 
                {                            
                    console.error("METHOD NOT ALLOWED!");
                }
                if (jqXHR.status === 500) 
                {                            
                    console.error("BATEO CON EL SERVIDOR");
                }
            })
            .always(function () {
                console.log("LEGA AL ALWAYS");
            })
        }
        
    function ModificarValoresInformativos()
    {

        var cant = 0;
        var total = 0;
        $('input[id$="cantidad"]', 'td').each(function(j) {
            cant += parseFloat($(this).val());
            $('input[id$="importe"]').eq(j).val(($('input[id$="precio"]').eq(j).val() * $(this).val()).toFixed(2));
            total += parseFloat($('input[id$="importe"]').eq(j).val());
            $('#cantArticulos-input').val(cant);
            var igv = ((total * 18) / 118).toFixed(2);
            $('#total').val(total);
            $('#subtotal').val((total - igv).toFixed(2));
            $('#igv').val(igv);
        });

    }

})(jQuery);








// (function ($) {
//             $(document).ready(function () {
//                 document.getElementById("nota_credito_venta").onchange=VentaChanged;
//                 inicializarTabla();
//                 ThingsToDo();
//             });

//             function VentaChanged () {
//                 ThingsToDo();
//             }

//             function ThingsToDo () {
//                 var venta = $('#nota_credito_venta option:selected');
                
//                 $('#venta-fecha').val(venta.data('ventaFecha'));
//                 $('#venta-cliente').val(venta.data('ventaCliente'));
//                 $('#venta-vendedor').val(venta.data('ventaVendedor'));
//                 $('#venta-centrodecosto').val(venta.data('ventaCentrodecosto'));
//                 $('#venta-formadepago').val(venta.data('ventaFormadepago'));
//                 $('#venta-observacion').val(venta.data('ventaObservacion'));

//                 var ventaId = venta.data('ventaId');
//                 $('#aceptarBtn').attr('href', "/montero/web/app_dev.php/venta/show/" + ventaId);

//                 // console.log("Danyer");                
//                 // debugger
//                 // requestData = {"numerodedocumento" : venta};
//                 // url = "{{ path('notacredito_ajax') }}";
//                 // $.get(url, requestData, function(){
                    
//                 // });
                
//                 $.ajax({
//                     method  : "GET",
//                     url     : "/montero/web/app_dev.php/notacredito/ajax",
//                     data    : { 'ventaId' : venta.data('ventaId')},
//                     dataType : 'json'
//                 })
//                 .done(function (data) { 
//                     var articulosVenta = JSON.parse(data.datos);
//                     console.log("CANTIDAD = " + articulosVenta);                    
//                     // debugger

//                     $('#table-div').children().remove();
//                     $('#table-div').html(
//                         '<label>Artículos:</label>' + 
//                         '<br/>' + 
//                         '<table id="otherTable" class="table-bordered table-responsive table-striped hover">' + 
//                             '<thead>' +
//                             '<tr>' +
//                                 '<th>Sección</th>' +
//                                 '<th>Artículo</th>' +
//                                 '<th>Precio</th>' +
//                                 '<th>Cantidad</th>' +
//                                 '<th>Importe</th>' +
//                             '</tr>' + 
//                             '</thead>' + 
//                             '<tbody></tbody>' + 
//                             '</table><br/><br/>');

//                     // Agregar por cada articulo en articulosVenta una fila en la tabla de los articulos relacionados con la venta
//                     var cantArticulos = 0;
//                     var lim = articulosVenta.length;                    
//                     for (var i = 0; i < lim; i++) 
//                     {
//                         var articuloVenta = articulosVenta[i];
//                         $('tbody', '#otherTable').append(
//                             '<tr>'+ 
//                                 '<td>' + articuloVenta.seccion + '</td>' + 
//                                 '<td>' + articuloVenta.articulo + '</td>' + 
//                                 '<td>' + articuloVenta.precio + '</td>' + 
//                                 '<td><input type="text" class="form-control" id="cantidad' + i + '" value="'+ articuloVenta.cantidad +
//                                     '"></input></td>' + 
//                                 '<td>' + articuloVenta.importe + '</td>' + 
//                             '</tr>'
//                             );
//                         cantArticulos += articuloVenta.cantidad;
//                     }

//                     inicializarTabla();
//                     // debugger
//                     $('#cantArticulos-input').val(cantArticulos);

//                     $('input', 'td').bind('keyup', function (i) {
//                         var cant = 0;
//                         $('input', 'td').each(function () {
//                             cant += parseFloat($(this).val());
//                         });
//                         $('#cantArticulos-input').val(cant);                        
//                     });

//                     console.log("DONE");
//                 })
//                 .fail(function (jqXHR, exception) {
//                     alert("Falló");
//                     if (jqXHR.status === 405) 
//                     {                            
//                         console.error("METHOD NOT ALLOWED!");
//                     }
//                     if (jqXHR.status === 500) 
//                     {                            
//                         console.error("BATEO CON EL SERVIDOR");
//                     }
//                 })
//                 .always(function () {
//                     console.log("LEGA AL ALWAYS");
//                 })
//             }

//             function inicializarTabla() 
//             {
//                 var table = $('#otherTable').DataTable({
//                     "bPaginate": true,
//                     "bLengthChange": true,
//                     "bSort": true,
//                     //"sDom":'<"top" l>t<"bottom" ip><"clear">',
//                     "bInfo": true,
//                     "bAutoWidth": false,
//                     "pagingType": "input", 
//                     "scrollX": true, 
//                     "scrollCollapse": true,
//                     "orderCellsTop": true,
//                     "fixedHeader": true,
//                     "lengthMenu":   [[5, 10, 20, 25, 50, -1], [5, 10, 20, 25, 50, "Todos"]],
//                     "iDisplayLength":   10,
//                     "dom" : '<"table_top"<"table_pagiation" p><"table_length"l><i>>tr<"table_top"<"table_pagiation" p><"table_length"l><i>>',
//                     'oLanguage':{
//                         "sZeroRecords": "No existen datos que mostrar.",
//                         "sEmptyTable": "No existen datos que mostrar.",
//                         "sLoadingRecords": "Cargando...",
//                         "sInfo": "Mostrando _START_ - _END_ de _TOTAL_", /*"De un total de _TOTAL_ entradas mostrando de (_START_ a _END_)",*/
//                         "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
//                         "sInfoFiltered": "(Filtradas de _MAX_ entradas en total)",
//                         "sLengthMenu": "_MENU_",
//                         "sProcessing": "Procesando...",
//                         "sSearch": "Buscar:",
//                         "oPaginate":{
//                             "sNext": '<i class="fa fa-chevron-right" ></i>',
//                             "sPrevious": '<i class="fa fa-chevron-left" ></i>',
//                             "sFirst" : '<i class="fa fa-step-backward" ></i>',
//                             "sLast" : '<i class="fa fa-step-forward" >'
//                         }            
//                     }

//                 });
//             }
//         })(jQuery);