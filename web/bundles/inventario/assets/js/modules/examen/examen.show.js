var examenShow = function () {

    var treeCategories = null;

    var initExamenShow = function () {
        /** Init Events */
        initEvents();

    };
    
    function initEvents() {
        $('#btn-details').on('click', function () {
            $('#showAllExam').css('display', 'none');
            $('#details').css('display', 'block');
            /** Get data examen to show */
            getDataTest(1);
        });

        $('.closeDetails').on('click', function () {
            $('#details').css('display', 'none');
            $('#showAllExam').css('display', 'block');
        });
    }

    /**
     *  Get data examen to show
     *  @params idExamen
     *
     *  return void
     * */
    function getDataTest(idTest) {
        App.blocks('#details', 'refresh_toggle');
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
                    $('#nameShow').html(resp.data.name);
                    $('#dateShow').html(resp.data.date);
                    $('#secondNameShow').html(resp.data.secondName);
                    $('#ageShow').html(resp.data.age);
                    $('#lastNameShow').html(resp.data.lastName);
                    $('#docShow').html(resp.data.doc);
                    $('#responsableShow').html(resp.data.responsable);
                }

                initTreeGridDetails(resp.data.categories);

                App.blocks('#details', 'state_normal');
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
            $('#treeGridCategoriesShow').jqxTreeGrid('destroy');
            $('#details .treeGrid').append('<div id="treeGridCategoriesShow"></div>');
        }

        if (categories.length > 0) {
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

                }
            });


            treeCategories = $("#treeGridCategoriesShow").jqxTreeGrid(
                {
                    width: "80%",
                    source: dataAdapter,
                    altRows: true,
                    ready: function () {
                        $('#treeGridCategoriesShow').jqxTreeGrid('expandAll');
                    },
                    columns: [
                        {
                            text: 'Categoría',
                            dataField: 'name'
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


    return {
        init: function () {
            initExamenShow();
        }
    };
}();

jQuery(function () {
    examenShow.init();
});