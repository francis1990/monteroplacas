(function ($) {
    $(document).ready(function() {
        document.getElementById("form_fechafin").onchange=ThingsToDo;
    });

    function AdicionarCaja(fecha, cantidad)
    {
        $('tbody').append('<tr><td>' + fecha + '</td><td>' + cantidad + '</td></tr>');
    }

    function ThingsToDo () 
    {
        console.log("Hello from ThingsToDo()");
        $.ajax({
                method  : "GET",
                url     : "/monteroplacas/web/gasto/ajax",
                data    : { 'fechainicio' : $('input[id$="fechafin"]').val(), 'fechafin' : $('input[id$="fechafin"]').val()},
                dataType : 'json'
            })
            .done(function (data) { 
            	var cantidadencaja = JSON.parse(data.cantidadencaja);
                var cajas = JSON.parse(data.cajas);

                $('input[id$=cantidadencaja]').val(cantidadencaja);

                $('tbody', '.allTables').children('tr').remove();
                var lim = cajas.length;                    
                for (var i = 0; i < lim; i++) 
                {
                    AdicionarCaja(cajas[i].fechacuadre, cajas[i].cantidad);
                }

                console.log("DONE");
            })
            .fail(function (jqXHR, exception) {
//                alert("Falló");
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
})(jQuery);