var App = function () {
    var handleValidationForm = function (target, options) {
            var form = $(target);
            if (form.length) {
                form.validate(options);
                form.submit(function (e) {
                    $(".chzn_b").removeAttr('disabled');
                })
            }
        },
        handleFormCollection = function () {
            var container = $('#content');
            container.on('click', '.add-form-embedded', addFormEmbedded);
            container.on('click', '.remove-form-embedded', removeFormEmbedded);
        },
        addFormEmbedded = function (clickEvent) {
            clickEvent.preventDefault();
            var container = $($(this).data('container')),
                form = container.data('prototype'),
                index = container.data('index'),
                rowForm;

            rowForm = form.replace(/__name__/g, index);
            container.append(rowForm);
            container.data('index', index + 1); // aumentando el indice

            // Lanzando un evento notificando que fue adicionado el formulario
            container.trigger("embedded.form.added");
            //inicializar los select del formulario embebido
            $('.chzn_b').chosen()
        },
        removeFormEmbedded = function (clickEvent) {
            clickEvent.preventDefault();

            var rowForm = $(this).closest('tr'),
                parent = rowForm.parent();

            if (rowForm.length) {
                rowForm.remove();
                // Lanzando un evento notificando que fue eliminado el formulario
                parent.trigger("embedded.form.deleted");
            }
        };
    return {
        'handleFormCollection': handleFormCollection,
        'handleValidationForm': handleValidationForm
    }
}();