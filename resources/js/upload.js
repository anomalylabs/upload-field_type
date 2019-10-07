const Uppy = require('@uppy/core')
const XHRUpload = require('@uppy/xhr-upload')

require('@uppy/core/dist/style.css')

let fields = Array.prototype.slice.call(
    document.querySelectorAll('input[data-provides="anomaly.field_type.upload"]')
);

fields.forEach(function (field) {

    let wrapper = field.closest('div');
    let attachment = wrapper.querySelector('.attachment');
    let file = wrapper.querySelector('input[type="hidden"]');
    let remove = wrapper.querySelector('[data-remove="upload"]');

    if (remove) {
        remove.addEventListener('click', function () {

            file.value = '';

            attachment.remove();
        });
    }

    const uppy = Uppy()
        .use(Dashboard, {
            trigger: '#select-files'
        })
        .use(XHRUpload, {
            endpoint: 'https://api2.transloadit.com'
        })

    uppy.on('complete', (result) => {
        console.log('Upload complete! We’ve uploaded these files:', result.successful)
    });
});
