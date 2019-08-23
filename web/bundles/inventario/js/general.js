var x = $(document);
x.ready(inicializar);

function inicializar() {

    window.lock_screen = function () {
        $('.cp-overlay').remove();
        $('body').prepend('<div class="cp-overlay" style="position: fixed; z-index: 9999; left: 0; top: 0; right: 0; bottom: 0; background-color: rgba(100, 100, 100, 0.5)"><i class="fa fa-gear fa-spin fa-3x" style="z-index: 999; position: absolute; left: 48%; color: #fff; margin-top: 330px;"></i></div>');
    };

    window.unlock_screen = function () {
        $('.cp-overlay').remove();
    };


    $('#eliminar,.btn_eliminar,#btn_eliminar').click(function (e) {
        bootbox.confirm({
            message: "¿Está seguro que desea eliminar el elemento?",
            buttons: {
                confirm: {label: 'Aceptar', className: 'btn btn-primary'},
                cancel: {label: 'Cancelar', className: 'btn btn-defaul'}
            },
            callback: function (result) {
                if (result) {
                    lock_screen();
                    var urlElim= $('.eliminada').attr('data-url');
                    var id=$('.eliminada').parent().parent()[0].id;

                    $.ajax({
                        type: "POST",
                        url: urlElim,
                        data: {'id': id},
                        success: function (response) {
                            unlock_screen();
                            var table = $('.todasTablas').DataTable();

                                        table.row("#" + id).remove().draw();

                            }
                        , error: function () {
                            unlock_screen();
                        }
                    });
                }
                else
                    $('.eliminada').removeClass('eliminada');
            }
        });
    });

    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('.datepicker').datepicker({
        onSelect: function (date) {
            alert(date)
        },
        todayHighlight: true,
        pickTime: false,
        autoclose: true,
        language: 'es'
    });
        
    // $('.todasTablas tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Buscar '+title+'" class="datatable_input_buscar" style="width: 100%; padding: 3px; box-sizing: border-box;"/>' );
    // } );

    // Setup - add a text input to each footer cell
    $('.todasTablas thead tr').clone(true).appendTo( '.todasTablas thead' );
    $('.todasTablas thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="datatable_input_buscar" style="width: 100%;" placeholder="Buscar '+title+'" />' );

    $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );


    var table = $('.todasTablas').DataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bSort": true,
        //"sDom":'<"top" l>t<"bottom" ip><"clear">',
        "bInfo": true,
        "bAutoWidth": false,
        "pagingType": "input", 
        "scrollX": true, 
        "scrollCollapse": true,
        "orderCellsTop": true,
        "fixedHeader": true,
        "lengthMenu":   [[5, 10, 20, 25, 50, -1], [5, 10, 20, 25, 50, "Todos"]],
        "iDisplayLength":   10,
        "dom" : '<"table_top"<"table_pagiation" p><"table_length"l><i>>tr<"table_top"<"table_pagiation" p><"table_length"l><i>>',
        'oLanguage':{
            "sZeroRecords": "No existen datos que mostrar.",
            "sEmptyTable": "No existen datos que mostrar.",
            "sLoadingRecords": "Cargando...",
            "sInfo": "Mostrando _START_ - _END_ de _TOTAL_", /*"De un total de _TOTAL_ entradas mostrando de (_START_ a _END_)",*/
            "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "sInfoFiltered": "(Filtradas de _MAX_ entradas en total)",
            "sLengthMenu": "_MENU_",
            "sProcessing": "Procesando...",
            "sSearch": "Buscar:",
            "oPaginate":{
                "sNext": '<i class="fa fa-chevron-right" ></i>',
                "sPrevious": '<i class="fa fa-chevron-left" ></i>',
                "sFirst" : '<i class="fa fa-step-backward" ></i>',
                "sLast" : '<i class="fa fa-step-forward" >'

                /*"sNext": '<span><img src=""></span>',
                "sPrevious": '<span><img src=""></span>',
                "sFirst" : '<span><img src=""></span>',
                "sLast" : '<span><img src=""></span>'*/
            }            
        }

    });

    // // Apply the search
    // table.columns().every( function () {
    //     var that = this;
 
    //     $( 'input', this.footer() ).on( 'keyup change', function () {
    //         if ( that.search() !== this.value ) {
    //             that
    //                 .search( this.value )
    //                 .draw();
    //         }
    //     } );
    // } );

    // //Para mostrar los inputs de buscar en la cabeza de la tabla, sin esta línea de código se muestra el input en el pie de la tabla
    // $('.todasTablas tfoot tr').appendTo('.todasTablas thead');

    

   jQuery(".chosen-select").chosen();
    $('form ul.form-error').closest('div.form-group').addClass('has-error');
    $('.form-group.has-error input,.form-group.has-error select').on('keyup change', null, function () {
        if ($(this).siblings('ul.form-error').length > 0) {
            $(this).siblings('ul.form-error').remove();
            $(this).closest('div.form-group').removeClass('has-error')
        }
    });

    $('form label.required').each(function (i) {
        var elem = $('<span class=" text-red" style="padding-right: 3px"> * </span>')[0];
        $(this)[0].parentNode.appendChild(elem);
    });

    /*Mostrar la mascara cuando se realiza una acción en los adicionar y cancelar*/
    $('#btn_adicionar,#btnCancelar').click(function () {
        window.lock_screen();
    });
        $('.flash-mensage').fadeOut(3600, function () {
            $('.flash-mensage').remove();
        });
    $('#btn_eliminar,.btn_eliminar').click(function () {
        $(this).addClass('eliminada');
    })
}



