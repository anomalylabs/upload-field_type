(function (window, document) {

    let fields = Array.from(
        document.querySelectorAll('input[data-provides="anomaly.field_type.upload"]')
    );

    fields.forEach(function (field) {

        let wrapper = field.closest('div');
        let attachment = wrapper.querySelector('.attachment');
        let file = wrapper.querySelector('input[type="hidden"]');
        let remove = wrapper.querySelector('[data-remove="upload"]');

        remove.addEventListener('click', function (event) {

            file.value = '';

            attachment.remove();
        });
    });
})(window, document);
