$(function () {

    var examTable = $('#exam-table');
    $('#newExam').css('display', 'none');
    init();


    /**
     * Init the Exam Table
     */
    function init(){
        //Init Exam Table
        examTable.DataTable({
            "ordering": false,
            "aLengthMenu": [7],
            'ajax': {
                'url': "getAllData",
                'type': 'GET'
            },
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "No existen procesos",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": ">>",
                    "sPrevious": "<<"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            rowCallback: function (row, data) {
                $(row).data('data', data);
                App.blocks('#newExam', 'state_normal');
            },
            "columns": [
                {data: 'codigo'},
                {data: 'paciente'},
                {data: 'doctor'},
                {data: 'fecha'}
            ]
        });

    }

    $('#btn-addExam').on('click', function (e) {
        createCategoryTree();
        $('#showAllExam').css('display', 'none');
        $('#newExam').css('display', 'block');
    });

    $('#btnSaveExam').on('click', function (e) {
        showPrincipalView();
    });

    $('#btnCancelSaveExam').on('click', function (e) {
        showPrincipalView();
    });

    function createCategoryTree() {
        $('#categoryTree').jstree("destroy");
        $('#categoryTree').jstree({
            'core': {
                "themes" : {
                    "responsive": false
                },
                "check_callback" : true,
                'data': [
                    { "id" : "h1", "parent" : "#", "text" : "Hematología", "data":{"name":"Hematología", "parent":"#"} },
                    { "id" : "h1.1", "parent" : "h1", "text" : "AC.Fólico", "data":{"name":"AC.Fólico", "parent":"h1"} },
                    { "id" : "h1.2", "parent" : "h1", "text" : "Coalgulación y Sangría", "data":{"name":"Coalgulación y Sangría", "parent":"h1"} },
                    { "id" : "b1", "parent" : "#", "text" : "Bioquímica", "data":{"name":"Bioquímica", "parent":"#"} },
                    { "id" : "b1.1", "parent" : "b1", "text" : "AC.Úrico", "data":{"name":"AC.Úrico", "parent":"b1"} },
                    { "id" : "b1.2", "parent" : "b1", "text" : "Amilasa", "data":{"name":"Amilasa", "parent":"b1"} },
                    { "id" : "b1.3", "parent" : "b1", "text" : "Cálculo Renal", "data":{"name":"Cálculo Renal", "parent":"b1"} }
                ]
            },
            /*"checkbox": {
                "three_state": false,
                "cascade": 'up',
                "keep_selected_style": false
            },*/
            "types" : {
                "default" : {
                    "icon": "fa fa-hospital-o icon-state-success icon-lg"
                },
                "file" : {
                    "icon": "fa fa-hospital-o icon-state-success icon-lg"
                }
            },
            "state": {"key": "categoryTree"},
            'plugins': ["wholerow", "checkbox", "types"]
        });
    }

    function showPrincipalView(){
        $('#showAllExam').css('display', 'block');
        $('#newExam').css('display', 'none');
    }

});