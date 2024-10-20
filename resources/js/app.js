import './bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

$(document).ready(function () {
    $('#btnImport').click(function (event) {
        event.preventDefault();
        const titleWord = $('#titleWord').val();
        const token = $('input[name="_token"]').val();

        importArticle(titleWord, token);
    });

    $('#btnSearch').click(function (event) {
        event.preventDefault();
        const keyWord = $('#keyWord').val();
        const token = $('input[name="_token"]').val();

        searchArticle(keyWord, token);
    });

    $('.resultTable').on('click', 'a', function (event) {
        event.preventDefault();
        showArticlePreview($(this).attr('href'));
    });
});

const showArticlePreview = (link) => {
    let articlePreview = $('.articlePreview');
    $.ajax({
        url: link,
        type: 'GET',
        success: (response) => articlePreview.html(response).removeClass('d-none'),
        error: (xhr, status, errors) => articlePreview.html(errors).removeClass('d-none'),
    });
}
const searchArticle = (keyWord, token) => {
    $('.articlePreview').html('').addClass('d-none');
    $('.resultTable').remove('table').addClass('d-none');
    $('.resultSearch').html('').addClass('d-none');

    $.ajax({
        url: '/search',
        type: 'POST',
        data: {keyWord, _token: token},
        success: (response) => handleSearchSuccess(response),
        error: (xhr, status, errors) => handleSearchError(xhr, errors)
    });
}
const handleSearchSuccess = (response) => {
    $('#keyWord').removeClass('is-invalid');
    $('.feedback').removeClass('invalid-feedback').text('');
    $('.resultSearch')
        .html('')
        .append(`<p>Найдено: ${response.length} совпадений.</p>`)
        .removeClass('d-none');

    showResultTable(response);
}
const handleSearchError = (xhr, errors) => {
    if (xhr.status === 422) {
        $('#keyWord').addClass('is-invalid');
        $('.feedback').addClass('invalid-feedback').text('Исправьте ошибки в поле.');
    } else {
        $('.resultSearch')
            .html('')
            .append('<p>Ошибка поиска.</p>')
            .append(`<p>Error: ${errors}</p>`)
            .removeClass('d-none');
    }
    // hideResultTable();
}
const showResultTable = (response) => {
    const table = $('<table class="table table-striped"></table>');
    response.forEach(item => {
        const row = `
            <tr>
                <td>
                <a href="${item.link}" class="link-primary">${item.title}</a>
                </td>
                <td>${item.count} вхождений</td>
            </tr>
        `;
        table.append(row);
    });

    $('.resultTable')
        .html('')
        .append(table)
        .removeClass('d-none');
}
const importArticle = (titleWord, token) => {
    $.ajax({
        url: '/import',
        type: 'POST',
        data: {titleWord, _token: token},
        xhr: progressbarUpdate,
        success: (response) => handleImportSuccess(response),
        error: (xhr, status, errors) => handleImportError(xhr, errors)
    });
};
const handleImportSuccess = (response) => {
    $('#titleWord').removeClass('is-invalid');
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
        $('#titleWord').addClass('is-invalid');
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
