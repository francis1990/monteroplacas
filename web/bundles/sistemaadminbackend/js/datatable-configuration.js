var x = $(document);
x.ready(inicializar);

function inicializar() {
    // $('.allTables tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Buscar '+title+'" class="datatable_input_buscar"/>' );
    // } );

    // Setup - add a text input to each footer cell
    $('.allTables thead tr').clone(true).appendTo( '.allTables thead' );
    $('.allTables thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="datatable_input_buscar" placeholder="Buscar '+title+'" />' );

    $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    var table = $('.allTables').DataTable({
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
    // $('.allTables tfoot tr').appendTo('.allTables thead');
}