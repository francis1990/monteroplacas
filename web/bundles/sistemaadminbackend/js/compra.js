(function ($) {
    $(document).ready(function () {
        App.handleFormCollection();
        App.handleValidationForm('#form-compra');
        container = $('#content');
        rellenarRuc();
        function rellenarRuc(){
           if( $('#compra_proveedor').val()!=null)
               $('#ruc_proveedor').val($('#compra_proveedor option:selected').data('ruc'))
        }
        $('#compra_proveedor').change(function(){
            rellenarRuc()
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
            $('#compra_cantidaddearticulo').val(cant.toFixed(2));
            $('#compra_montototalpagado').val(imp.toFixed(2))

        }
        container.on('click', '.remove-form-embedded,.add-form-embedded', function (event) {
            calcularMontos();
        });
        container.on('keyup', '.cantidad-embed', function (event) {

            var $this = $(this),
                number = $this.attr('id').split('_')[2];
            var precio=0;
            var cant=0;
            precio= $('#venta_articuloventas_' + number + '_precio').val();
            cant= $('#venta_articuloventas_' + number + '_cantidad').val();
            
            
            var imp=0;
            if(isNaN(precio) || precio==0){
            if($('#tipoprecio').val()=='v')
            {
                precio= $('#compra_articulocompras_' + number + '_articulo option:selected').data('pcompra');
            }
            else{
                precio=  $('#compra_articulocompras_' + number + '_articulo option:selected').data('pmayor');
            }}
        
            var importe=isNaN(precio*$this.val())?0:(precio*$this.val()).toFixed(2);
            $('#compra_articulocompras_' + number + '_precio').val(precio);
            $('#compra_articulocompras_' + number + '_importe').val(importe);

            calcularMontos()
        });
        
        container.on('keyup', '.precio-embed', function (event) {
            var $this = $(this),
                number = $this.attr('id').split('_')[2];
            var precio=0;
            var cantidad=0;
            precio= $('#compra_articulocompras_' + number + '_precio').val();
            cantidad= $('#compra_articulocompras_' + number + '_cantidad').val();
            
//            if($('#tipoprecio').val()=='v')
//            {
//                precio= $('#venta_articuloventas_' + number + '_articulo option:selected').data('pventa');
//            }
//            else{
//                precio=  $('#venta_articuloventas_' + number + '_articulo option:selected').data('pmayor');
//            }

            var importe=isNaN(precio*cantidad)?0:(precio*cantidad).toFixed(2);
//            $('#venta_articuloventas_' + number + '_precio').val(precio);

            $('#compra_articulocompras_' + number + '_importe').val(importe);
            calcularMontos()
        });

    });

})(jQuery);

