(function ($) {
    $(document).ready(function () {
        App.handleFormCollection();
        App.handleValidationForm('#form-venta');
        container = $('#content');

        function rellenarSerie(){
            if($('#venta_seriedoc').length!=0){
            $('#venta_serie').val('');
            $('#venta_numerodedocumento').val('');
            var option = $('#venta_documento option:selected');
            var series=option.data('serie1').split('-');
            var series2=option.data('serie2').split('-');
            var series3=option.data('serie3').split('-');
            $('#venta_seriedoc option').remove() ;
            $('#venta_seriedoc').append(
                '<option value="'+series[1]+'">'+series[0]+' | '+series[1]+'</option>'+
                '<option value="'+series2[1]+'">'+series2[0]+' | '+series2[1]+'</option>'+
                '<option value="'+series3[1]+'">'+series3[0]+' | '+series3[1]+'</option>'
            );
        }
            else
            {
                $('#venta_serie,#venta_documento,#venta_numerodedocumento').attr('disabled',true);
            }

        }
        container.on('click', '.remove-form-embedded,.add-form-embedded', function (event) {
            console.log('ueueueuue')
            calcularMontos();
        });
        function generarNumero()
        {
            $('#venta_serie').val(($('#venta_seriedoc option:selected').html()).split(' | ')[0]);
            var consecutivo=(($('#venta_seriedoc').val())*1)+1;
            $('#venta_numerodedocumento').val( consecutivo)
        }

        if( $('#venta_documento').val() !=null){
            rellenarSerie();
        }
       if( $('#venta_seriedoc').val()!=null){
           generarNumero()
       }
        if($('#venta_cliente').val()!=null)
             $('#venta_numerodecuenta').val($('#venta_cliente option:selected').data('cuenta'));
        $('#venta_documento').change(function(){
            rellenarSerie()
             generarNumero()
        }) ;
        $('#venta_seriedoc').change(function(){
            generarNumero()
        });
        $('#venta_cliente').change(function(){
            $('#venta_numerodecuenta').val($(this).data('cuenta'));
        });
        $('form').submit(function(){
            if($('#content form tbody tr').length!=0){
                $('#venta_seriedoc').addClass('disabled');
                $('#venta_serie').removeAttr('disabled');
                $('#venta_numerodedocumento').removeAttr('disabled');
                $('#venta_documento').removeAttr('disabled');
                return true;
            }
            else
                return false;

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
            $('#venta_cantidaddearticulos').val(cant.toFixed(2));
            $('#venta_montototalapagar').val(imp.toFixed(2))
        }
        container.on('keyup', '.cantidad-embed', function (event) {
            var $this = $(this),
                number = $this.attr('id').split('_')[2];
            var precio=0;
            precio= $('#venta_articuloventas_' + number + '_precio').val();

            if(isNaN(precio) || precio==0){
            if($('#tipoprecio').val()=='v')
            {
                precio= $('#venta_articuloventas_' + number + '_articulo option:selected').data('pventa');
            }
            else{
                precio=  $('#venta_articuloventas_' + number + '_articulo option:selected').data('pmayor');
            }}

            var importe=isNaN(precio*$this.val())?0:(precio*$this.val()).toFixed(2);
            $('#venta_articuloventas_' + number + '_precio').val(precio);

            $('#venta_articuloventas_' + number + '_importe').val(importe);
            calcularMontos()
        });
        
        container.on('keyup', '.precio-embed', function (event) {
            var $this = $(this),
                number = $this.attr('id').split('_')[2];
            var precio=0;
            var cantidad=0;
            precio= $('#venta_articuloventas_' + number + '_precio').val();
            cantidad= $('#venta_articuloventas_' + number + '_cantidad').val();
            var importe=!isFinite(precio*cantidad)?0:(parseFloat(precio*cantidad)).toFixed(2);
            $('#venta_articuloventas_' + number + '_importe').val(importe);
            calcularMontos()
        });
    });

})(jQuery);

