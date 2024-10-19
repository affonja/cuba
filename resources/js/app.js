import './bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

$(document).ready(function () {
    $('#btnImport').click(function (event) {
        event.preventDefault();
        const keyWord = $('#keyWord').val();
        const token = $('input[name="_token"]').val();

        importArticle(keyWord, token);
    });
});

const importArticle = (keyWord, token) => {
    $.ajax({
        url: '/import',
        type: 'POST',
        data: {keyWord, _token: token},
        xhr: progressbarUpdate,
        success: (response) => handleImportSuccess(response),
        error: (xhr, status, errors) => handleImportError(xhr, errors)
    });
};
const handleImportSuccess = (response) => {
    $('#keyWord').removeClass('is-invalid');
    $('.feedback').removeClass('invalid-feedback').text('');
    $('.statusImport')
        .html('')
        .append('<p>Импорт завершен.</p>')
        .append(`<p>Найдена статья по адресу: <a href="${response.link}">${response.link}</a></p>`)
        .append(`<p>Время обработки: ${response.executionTime}</p>`)
        .append(`<p>Кол-во слов: ${response.wordsCount}</p>`)
        .removeClass('d-none');

    updateArticlesTable();
};
const handleImportError = (xhr, errors) => {
    if (xhr.status === 422) {
        $('#keyWord').addClass('is-invalid');
        $('.feedback').addClass('invalid-feedback').text('Исправьте ошибки в поле.');
    } else {
        $('.statusImport')
            .html('')
            .append('<p>Ошибка импорта.</p>')
            .append(`<p>Error: ${errors}</p>`)
            .removeClass('d-none');
    }
};
const updateArticlesTable = () => {
    $.ajax({
        url: '/updTable',
        type: 'GET',
        success: (response) => {
            $('#articlesTable').html(response);
        }
    });
};
const progressbarUpdate = () => {
    $('#progress-bar').css('width', '0%');
    let xhr = new window.XMLHttpRequest();
    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            let percentComplete = e.loaded / e.total * 100;
            $('#progress-bar').css('width', percentComplete + '%');
        }
    };
    return xhr;
}
