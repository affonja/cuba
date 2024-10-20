<table class="table table-striped" id="articlesTable">
    <thead>
    <tr>
        <th>{{ __('Название cтатьи') }}</th>
        <th>{{ __('Ссылка') }}</th>
        <th>{{ __('Размер статьи') }}</th>
        <th>{{ __('Кол-во слов') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->title }}</td>
            <td><a href="{{ $article->link }}">{{ $article->link }}</a></td>
            <td>{{ $article->size }} Kb</td>
            <td>{{ $article->wordsCount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
