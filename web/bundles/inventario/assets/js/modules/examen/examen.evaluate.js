var testEvaluate = function() {
    
    var treeCategories = null;
    var rowData = null;
    
    var initTestEvaluate = function(){

        /** Init Events */
        initEvents();
    };

    function initEvents() {
        $('#editTest').on('submit', function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
        });

        $('#btn-evalTest').on('click', function (e) {
            $('#showAllExam').css('display', 'none');
            $('#formEvaluate').css('display', 'block');

            /** Get data examen to show */
            getDataTest(1);
        });

        $('.closeEval').on('click', function () {
            $('#formEvaluate').css('display', 'none');
            $('#showAllExam').css('display', 'block');
        });

        $('#btnCancelEvaluate').on('click', function (e) {
            $('#formEvaluate').css('display', 'none');
            $('#showAllExam').css('display', 'block');
        });

        $('#btnAceptEvaluate').on('click', function () {
            var success = true;
            var node = null;
            if(rowData.length > 0){
                for (var i = 0; i < rowData.length; i++) {
                    if(rowData[i].editable) {
                        node = $("#treeGridCategoriesEvaluate").jqxTreeGrid('getRow', rowData[i].id);
                        if(node.valor) {
                            rowData[i].valor  = node.valor
                        } else {
                            success = false;
                            break;
                        }
                    }
                }
            }

            if(success) {
                evaluateTest(1, rowData);
            } else  {
                /** Notify error */
                notifyError('Existen evaluaciones sin completar.');
            }
        });
    }

    /**
     *  Get data examen to show
     *  @params idExamen
     *
     *  return void
     * */
    function getDataTest(idTest) {
        App.blocks('#formEvaluate', 'refresh_toggle');
        $.ajax({
            method: "GET",
            url: "getDataTest",
            cache: false,
            data: {
                idTest: idTest
            },
            success: function (resp) {
                if (resp.data) {
                    /** Fill data to details */
                    $('#nameEval').html(resp.data.name);
                    $('#dateEval').html(resp.data.date);
                    $('#secondNameEval').html(resp.data.secondName);
                    $('#ageEval').html(resp.data.age);
                    $('#lastNameEval').html(resp.data.lastName);
                    $('#docEval').html(resp.data.doc);
                    $('#responsableEval').html(resp.data.responsable);
                }

                initTreeGridDetails(resp.data.categories);
                App.blocks('#formEvaluate', 'state_normal');
            },
            error: function (resp) {

            }
        });

    };

    /**
     *  InitTreeGridDetails
     *
     *  @params object data categories to the Test
     *
     *  return void
     * */
    function initTreeGridDetails(categories) {

        if(treeCategories){
            $('#treeGridCategoriesEvaluate').jqxTreeGrid('destroy');
            $('#formEvaluate .treeGrid').append('<div id="treeGridCategoriesEvaluate"></div>');
        }

        if (categories.length > 0) {

            rowData = categories;

            var source =
            {
                dataType: "json",
                dataFields: [
                    {name: 'id', type: 'number'},
                    {name: 'parent', type: 'number'},
                    {name: "name", type: "string"},
                    {name: "valor", type: "string"}
                ],
                hierarchy: {
                    keyDataField: {name: 'id'},
                    parentDataField: {name: 'parent'}
                },
                id: 'id',
                localData: categories
            };


            var dataAdapter = new $.jqx.dataAdapter(source, {
                loadComplete: function () {
                    setTimeout(function () {
                        /** Lock rows */
                        $.each(rowData, function (key, value) {
                            if (!value.editable) {
                                $("#treeGridCategoriesEvaluate").jqxTreeGrid('lockRow', value.id);
                            }
                        });

                    }, 100);
                }
            });


            treeCategories = $("#treeGridCategoriesEvaluate").jqxTreeGrid(
                {
                    width: "100%",
                    source: dataAdapter,
                    altRows: true,
                    editable: true,
                    editSettings: {
                        saveOnPageChange: true,
                        saveOnBlur: true,
                        saveOnSelectionChange: true,
                        cancelOnEsc: true,
                        saveOnEnter: true,
                        editSingleCell: true,
                        editOnDoubleClick: true,
                        editOnF2: true
                    },
                    ready: function () {
                        $('#treeGridCategoriesEvaluate').jqxTreeGrid('expandAll');
                    },
                    columns: [
                        {
                            text: 'Categoría',
                            dataField: 'name',
                            editable: false,
                            align: 'center'
                        },
                        {
                            text: 'Valor',
                            dataField: 'valor',
                            align: 'center',
                            cellsAlign: 'center'
                        }
                    ]
                });
        }
    }
    
    function evaluateTest(idTest, categories) {
        App.loader('show');
        $.ajax({
            method: "GET",
            url: "evaluateTest",
            cache: false,
            data: {
                params: {
                    idTest: idTest,
                    categories: categories
                }
            },
            success: function (resp) {
                App.loader('hide');
                $('#formEvaluate').css('display', 'none');
                $('#showAllExam').css('display', 'block');
            },
            error: function (resp) {

            }
        });
    }

    function notifyError(msg) {
        $.notify({
                icon: "fa fa-times",
                message: msg
            },
            {
                element: 'body',
                type: 'danger',
                allow_dismiss: true,
                newest_on_top: true,
                showProgressbar: false,
                placement: {
                    align: "right"
                },
                offset: 20,
                spacing: 10,
                z_index: 1033,
                delay: 5000,
                timer: 1000,
                animate: {
                    enter: 'animated wobble',
                    exit: 'animated bounceOut'
                }
            });
    }
    return {
        init: function () {
            initTestEvaluate();
        }
    };
}();

jQuery(function(){ testEvaluate.init(); });
