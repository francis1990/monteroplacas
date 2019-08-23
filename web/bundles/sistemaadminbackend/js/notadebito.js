(function ($) {
    $(document).ready(function() {
        var content = $('#content');
        content.on('click', '.add-form-embedded', function (clickEvent) {            
            clickEvent.preventDefault();
            var articuloVenta = {seccion: 1, articulo: null, precio: 0, cantidad: 0, importe: 0};
            AdicionarArticulo(articuloVenta);
            ParaCuandoCambienValoresInputs();
            $('select[id$="articulo"]', 'td').last().removeAttr('readonly');
            var container = $('#articulonotadebitos-tbody');
            // Lanzando un evento notificando que fue adicionado el formulario
            container.trigger("embedded.form.added");
            //inicializar los select del formulario embebido
            $('.chzn_b').chosen()
        });

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
        document.getElementById("nota_debito_venta").onchange=ThingsToDo;

    });

    function AdicionarArticulo(articuloVenta)
    {
        var container = $('#articulonotadebitos-tbody');
        var form = container.data('prototype');
        var index = container.data('index');

        var rowForm = form.replace(/__name__/g, index);
        var htmlObject = $('<tr></tr>');
        htmlObject.append(rowForm);
        container.append(htmlObject);
        container.data('index', index + 1); // aumentando el indice
        htmlObject.find('label').remove();
        var inputElemsParent = htmlObject.children('div').children('div');
        htmlObject.find('div').remove();
        var collection = inputElemsParent.get();
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

        DarValorFila(articuloVenta);  
        
         $('input[id$="cantidad"]', 'td').bind('keyup', function () {
                    ModificarValoresInformativos();
                });
                $('input[id$="precio"]', 'td').bind('keyup', function () {
                    ModificarValoresInformativos();
                });
    }
    
    function AdicionarArticuloSinOpcionEliminar(articuloVenta)
    {
        var container = $('#articulonotadebitos-tbody');
        var form = container.data('prototype');
        var index = container.data('index');

        var rowForm = form.replace(/__name__/g, index);
        var htmlObject = $('<tr></tr>');
        htmlObject.append(rowForm);
        container.append(htmlObject);
        container.data('index', index + 1); // aumentando el indice
        htmlObject.find('label').remove();
        var inputElemsParent = htmlObject.children('div').children('div');
        htmlObject.find('div').remove();
        var collection = inputElemsParent.get();
        htmlObject.append('<td>' + collection[0].innerHTML+ '</td>' + //seccion
        '<td>' + collection[1].innerHTML + '</td>' +                  //articulo
        '<td>' + collection[2].innerHTML + '</td>' +                  //precio
        '<td>' + collection[3].innerHTML + '</td>' +                  //cantidad
        '<td>' + collection[4].innerHTML + '</td>'                   //importe
        );
        var thead = container.closest('thead');
        thead.find('tr').append('<td></td>');

        DarValorFila(articuloVenta);  
        
         $('input[id$="cantidad"]', 'td').bind('keyup', function () {
                    ModificarValoresInformativos();
                });
                $('input[id$="precio"]', 'td').bind('keyup', function () {
                    ModificarValoresInformativos();
                });
    }

    function DarValorFila(articuloVenta)
    {
        var container = $('#articulonotadebitos-tbody');
        if (articuloVenta.articulo != null) 
        {
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
        }

        container.find('tr:last').find('input').eq(0).val(articuloVenta.precio);
        container.find('tr:last').find('input').eq(1).val(articuloVenta.cantidad);
        container.find('tr:last').find('input').eq(2).val(articuloVenta.importe);
    }

    function ParaCuandoCambienValoresInputs()
    {
        $('input[id$="cantidad"]', 'td').bind('keyup', function (i) {
            var cant = 0;
            $('input[id$="cantidad"]', 'td').each(function (j) {
                cant += parseFloat($(this).val());
                $('input[id$="importe"]').eq(j).val($('input[id$="precio"]'). eq(j).val() * $(this).val());
            });
            $('#cantArticulos-input').val(cant);                        
        });
    }

    function ThingsToDo () {
        var venta = $('#nota_debito_venta option:selected');
            
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
                url     : "/monteroplacas/web/notadebito/ajax",
                data    : { 'ventaId' : venta.data('ventaId')},
                dataType : 'json'
            })
            .done(function (data) { 
                var articulosVenta = JSON.parse(data.datos);                                   
                $('#articulonotadebitos-tbody').children('tr').remove();
                // Agregar por cada articulo en articulosVenta una fila en la tabla de los articulos relacionados con la venta
                var cantArticulos = 0;
                var lim = articulosVenta.length;                    
                for (var i = 0; i < lim; i++) 
                {
                    var articuloVenta = articulosVenta[i];
                    AdicionarArticuloSinOpcionEliminar(articuloVenta);                    
                    cantArticulos += articuloVenta.cantidad;
                }

                
                // debugger
                $('#cantArticulos-input').val(cantArticulos);
                ParaCuandoCambienValoresInputs();
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