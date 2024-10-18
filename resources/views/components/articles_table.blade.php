<table class="table table-striped" id="articlesTable">
    <thead>
    <tr>
        <th>Название cтатьи</th>
        <th>Ссылка</th>
        <th>Размер статьи</th>
        <th>Кол-во слов</th>
    </tr>
    </thead>
    <tbody>
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->title }}</td>
            <td><a href="{{ $article->link }}">{{ $article->link }}</a></td>
            <td>{{ $article->size }}</td>
            <td>{{ $article->wordsCount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
