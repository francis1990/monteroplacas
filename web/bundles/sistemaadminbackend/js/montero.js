    $(document).ready(function () {
        $('.eliminar_confirm').on('click',function () {
            var boton=$(this);
            bootbox.confirm('¿Está seguro de eliminar este registro?', function (result) {
                if (result) {
                    window.location=boton.data('url');
                }
            });
        });
        jQuery.validator.addMethod("mayorcero",
            function (value, element) {
                return value > 0;
            },
            "Introduzca un valor mayor que 0."
        );
    });


