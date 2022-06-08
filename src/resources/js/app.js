require('./bootstrap');

$("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

$('.dropzone-wrapper').on('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass('dragover');
});

$('.dropzone-wrapper').on('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
});

$('.dropzone-wrapper').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
    $("#file").click();
});

$('.dropzone-wrapper').on('drop', function (e) {
    e.stopPropagation();
    e.preventDefault();
    let file = e.originalEvent.dataTransfer.files;
    $("#file").prop("files", file);
    $('#fileName').html(file[0].name)

    let mode = $('#mode');

    if(mode.hasClass('d-none')) {
        mode.removeClass('d-none');
        mode.addClass('d-flex');
    }
});

$('#file').on('change', function () {
    let fileName = $(this).val().split("\\");
    let mode = $('#mode');
    $('#fileName').html(fileName[fileName.length - 1]);

    if(mode.hasClass('d-none')) {
        mode.removeClass('d-none');
        mode.addClass('d-flex');
    }
});

$('#selected-mode').change(function () {
    let whichPage = $('#whichPage');

    if (this.value === 'overlay') {
        whichPage.removeClass('d-none');
        whichPage.addClass('d-flex');
    } else {
        whichPage.removeClass('d-flex');
        whichPage.addClass('d-none');
    }
});

$('#type').change(function () {
    let paymentReference = $('#paymentReference');

    if (this.value === 'QRR') {
        paymentReference.removeClass('d-none');
        paymentReference.addClass('d-flex');
    } else {
        paymentReference.removeClass('d-flex');
        paymentReference.addClass('d-none');
    }
});

(function () {
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(init, 0);
    }, false);

    function init() {
        initLabels();
    }

    function initLabels() {
        var inputs = getAll('.text-field-input');
        inputs.forEach(function (el) {
            checkInput(el);

            el.addEventListener('focus', function (event) {
                event.target.parentElement.classList.add('field-active');
            });

            el.addEventListener('keypress', function (event) {
                event.target.parentElement.classList.add('field-active');
            });

            el.addEventListener('blur', function (event) {
                checkInput(event.target);
            });
        });
    }

    function getAll(selector) {
        return Array.prototype.slice.call(document.querySelectorAll(selector), 0);
    }

    function checkInput(target) {
        let parentElement = target.parentElement;

        if (target.value) {
            parentElement.classList.add('filled');
            parentElement.classList.remove('invalid');
            parentElement.classList.remove('field-active');
        } else {
            parentElement.classList.remove('field-active');
            parentElement.classList.remove('filled');
        }
    }
})();
