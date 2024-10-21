import './bootstrap';
import $ from 'jquery';
import {route} from 'ziggy-js';


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

// search
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
    resetResult();
    $.ajax({
        url: route('word.search'),
        type: 'POST',
        data: {keyWord, _token: token},
        success: (response) => handleSearchSuccess(response),
        error: (xhr, status, errors) => handleSearchError(xhr, errors)
    });
}
const resetResult = () => {
    $('.articlePreview').html('').addClass('d-none');
    $('.resultTable').html('').addClass('d-none');
    $('.resultSearch').html('').addClass('d-none');
}
const handleSearchSuccess = (response) => {
    $('#keyWord').removeClass('is-invalid');
    $('.feedback').removeClass('invalid-feedback').text('');
    $('.resultSearch')
        .html(`<p>Найдено: ${response.length} совпадений.</p>`)
        .removeClass('d-none');

    showResultTable(response);
}
const handleSearchError = (xhr, errors) => {
    if (xhr.status === 422) {
        $('#keyWord').addClass('is-invalid');
        $('.feedback').addClass('invalid-feedback').text('Исправьте ошибки в поле.');
    } else {
        $('.resultSearch')
            .html(`<p>Ошибка поиска.</p><p>Error: ${errors}</p>`)
            .removeClass('d-none');
    }
}
const showResultTable = (response) => {
    const table = $('<table class="table"></table>');
    response.forEach(item => {
        const row = `
            <tr class="lh-lg">
                <td><a href="${item.link}" 
                class="link-primary border-0 text-decoration-underline"
                >${item.title}</a></td>
                <td>${item.count} вхождений</td>
            </tr>
        `;
        table.append(row);
    });

    $('.resultTable').append(table).removeClass('d-none');
}

// import
const importArticle = (titleWord, token) => {
    // Show the spinner
    $('#spinner').show();

    $.ajax({
        url: route('article.import'),
        type: 'POST',
        data: {titleWord, _token: token},
        // xhr: progressbarUpdate,
        success: (response) => {
            handleImportSuccess(response);
            // Hide the spinner
            $('#spinner').hide();
        },
        error: (xhr, status, errors) => {
            handleImportError(xhr, errors);
            // Hide the spinner
            $('#spinner').hide();
        }
    });
};
const handleImportSuccess = (response) => {
    $('#titleWord').removeClass('is-invalid');
    $('.feedback').removeClass('invalid-feedback').text('');

    $('.statusImport')
        .html(`
            <p class="text-success text-uppercase fw-bold">Импорт завершен.</p>
            <p>Найдена статья по адресу: <a href="${response.link}">${response.link}</a></p>
            <p>Время обработки: ${response.executionTime} секунд</p>
            <p>Кол-во слов: ${response.wordsCount}</p>        
        `)
        .removeClass('d-none');

    updateArticlesTable();
};
const handleImportError = (xhr, errors) => {
    if (xhr.status === 422) {
        $('#titleWord').addClass('is-invalid');
        $('.feedback').addClass('invalid-feedback').text('Исправьте ошибки в поле.');
    } else {
        $('.statusImport')
            .html(`<p class="text-danger text-uppercase fw-bold">Ошибка импорта.</p><p>Error: ${xhr.responseJSON.errors}</p>`)
            .removeClass('d-none');
    }
};
const updateArticlesTable = () => {
    console.log(route('article.updTable'));
    $.ajax({
        url: route('article.updTable'),
        type: 'GET',
        success: (response) => $('#articlesTable').html(response)
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
