import './bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

$(document).ready(function () {
    $('#btnImport').click(function (event) {
        event.preventDefault();
        const keyWord = $('#keyWord').val();

        $.ajax({
            url: '/',
            type: 'POST',
            data: {
                keyWord: keyWord,
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                $('#keyWord').removeClass('is-invalid');
                $('.feedback').removeClass('invalid-feedback').text('');
                $('.statusImport')
                    .html('')
                    .append('<p>Импорт завершен.</p>')
                    .append('<p>Найдена статья по адресу: <a href=' +
                        response['link'] + '>' + response['link'] + '</a></p>')
                    .append('<p>Время обработки: ??</p>')
                    .append('<p>Кол-во слов: ' + response['wordsCount'] + '</p>')
                    .removeClass('d-none');

                $.ajax({
                    url: '/updTable',
                    type: 'GET',
                    success: function (response) {
                        $('#articlesTable').html(response);
                    }
                })
            },
            error: function (xhr, status, errors, error) {
                if (xhr.status === 422) {
                    $('#keyWord').addClass('is-invalid');
                    $('.feedback').addClass('invalid-feedback').text('Исправьте ошибки в поле.');
                } else {
                    console.error('Error:', error);
                }
            }
        });
    });
});
