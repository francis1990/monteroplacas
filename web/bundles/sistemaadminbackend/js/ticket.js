(function ($) {
    $(document).ready(function () {
        App.handleFormCollection();
        App.handleValidationForm('#form-venta');
        container = $('#content');
        function rellenarSerie(){
            if($('#ticket_seriedoc').length!=0){
            $('#ticket_serie').val('');
            $('#ticket_numerodedocumento').val('');
            var option = $('#ticket_documento option:selected');
            var series=option.data('serie1').split('-');
            var series2=option.data('serie2').split('-');
            var series3=option.data('serie3').split('-');
            $('#ticket_seriedoc option').remove() ;
            $('#ticket_seriedoc').append(
                '<option value="'+series[1]+'">'+series[0]+' | '+series[1]+'</option>'+
                '<option value="'+series2[1]+'">'+series2[0]+' | '+series2[1]+'</option>'+
                '<option value="'+series3[1]+'">'+series3[0]+' | '+series3[1]+'</option>'
            );
            }
            else
            {
                $('#ticket_serie,#ticket_documento,#ticket_numerodedocumento').attr('disabled',true);
            }

        }
        function generarNumero()
        {
            $('#ticket_serie').val(($('#ticket_seriedoc option:selected').html()).split(' | ')[0]);
            var consecutivo=(($('#ticket_seriedoc').val())*1)+1;
            $('#ticket_numerodedocumento').val( consecutivo)
        }

        if( $('#ticket_documento').val() !=null){
            rellenarSerie();
        }
       if( $('#ticket_seriedoc').val()!=null){
           generarNumero()
       }
        if($('#ticket_cliente').val()!=null)
             $('#ticket_numerodecuenta').val($('#ticket_cliente option:selected').data('cuenta'));
        $('#ticket_documento').change(function(){            
            rellenarSerie()
             generarNumero()
        }) ;
                
//        $('#ticket_documento').mousedown(function(){
//            generarNumero()
//        });
//        $('#ticket_seriedoc').mousedown(function(){
//            generarNumero()
//        });
        $('#ticket_seriedoc').change(function(){
            generarNumero()
        });
        $('#ticket_cliente').change(function(){
            $('#ticket_numerodecuenta').val($(this).data('cuenta'));
        });
        $('form').submit(function(){
            $('#ticket_seriedoc').addClass('disabled')
        });
        function calcularMontos(){
            var cant=0;
            var imp=0;
            $('.cantidad-embed').each(function(i){
              if(!isNaN((parseFloat($(this).val())))  )
                cant=cant+ parseFloat($(this).val());
            });

            $('.importe-embed').each(function(i){
                if(!isNaN(parseFloat($(this).val()))  )
                imp=imp+ parseFloat($(this).val());
            });
            $('#ticket_cantidaddearticulos').val(cant.toFixed(2));
            $('#ticket_montototalapagar').val(imp.toFixed(2))
        }
        container.on('keyup', '.cantidad-embed', function (event) {
            var $this = $(this),
                number = $this.attr('id').split('_')[2];
            var precio=0;
            var cant=0;
            precio= $('#ticket_articuloventas_' + number + '_precio').val();
                        
            if(isNaN(precio) || precio==0){            
            if($('#tipoprecio').val()=='v')
            {
                precio= $('#ticket_articuloventas_' + number + '_articulo option:selected').data('pventa');
            }
            else{
                precio=  $('#ticket_articuloventas_' + number + '_articulo option:selected').data('pmayor');
            }}

            var importe=isNaN(precio*$this.val())?0:(precio*$this.val()).toFixed(2);
            $('#ticket_articuloventas_' + number + '_precio').val(precio);
            $('#ticket_articuloventas_' + number + '_importe').val(importe);
            calcularMontos()
        });
        
        container.on('keyup', '.precio-embed', function (event) {
            var $this = $(this),
                number = $this.attr('id').split('_')[2];
            var precio=0;
            var cantidad=0;
            precio= $('#ticket_articuloventas_' + number + '_precio').val();
            cantidad= $('#ticket_articuloventas_' + number + '_cantidad').val();
            
//            if($('#tipoprecio').val()=='v')
//            {
//                precio= $('#venta_articuloventas_' + number + '_articulo option:selected').data('pventa');
//            }
//            else{
//                precio=  $('#venta_articuloventas_' + number + '_articulo option:selected').data('pmayor');
//            }

            var importe=isNaN(precio*cantidad)?0:(precio*cantidad).toFixed(2);
//            $('#venta_articuloventas_' + number + '_precio').val(precio);

            $('#ticket_articuloventas_' + number + '_importe').val(importe);
            calcularMontos()
        });
        
    });

})(jQuery);

