@extends('layouts.app')
@section('content')
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">Импорт статей
            </button>
            <button class="nav-link" id="nav-search-tab" data-bs-toggle="tab" data-bs-target="#nav-search"
                    type="button" role="tab" aria-controls="nav-search" aria-selected="false">Поиск
            </button>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
             tabindex="0">
            <form class="mt-5 mb-5" action="/" method="POST">
                @csrf
                <div class="row">
                    <label for="keyWord" class="form-label">Ключевое слово</label>
                </div>
                <div class="row">
                    <div class="col-3">
                        <input type="text" class="form-control" id="keyWord" aria-describedby="keyWord"
                               placeholder="Москва" name="keyWord">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary" id="btnImport">Импорт</button>
                    </div>
                    <div class="col-3">
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0"
                             aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                {{--                <div class="row">--}}
                {{--                <div id="keyWord" class="form-text">Поиск по ключевому слову</div>--}}
                {{--                </div>--}}
            </form>

            <div class="row">
                <div class="statusImport col-12">
                    импорт завершен. результаты
                </div>
            </div>

            <div class="row">
                <hr>
            </div>

            <div class="row">
                <div class="col-12 mt-3">
                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th>Название cтатьи</th>
                            <th>Ссылка</th>
                            <th>Размер статьи</th>
                            <th>Кол-во слов</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="nav-search" role="tabpanel" aria-labelledby="nav-search-tab" tabindex="0">
            <form class="mt-5 mb-5" action="">
                @csrf
                <div class="row">
                    <div class="col-3">
                        <input type="text" class="form-control" id="searchWord" aria-describedby="searchWord">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary">Найти</button>
                    </div>
                </div>
            </form>

            <div class="row">
                <hr>
            </div>

            <div class="row">
                <div class="resultSearch mt-3 mb-5">
                    <p>Найдено XXX совпадений</p>
                </div>
            </div>

            <div class="row">
                <div class="resultTable col-6">
                    <table class="table table-striped ">
                        <tr>
                            <td>Название</td>
                            <td>XX вхождений</td>
                        </tr>
                    </table>
                </div>

                <div class="articlePreview col-6">
                    nтекст статьи
                </div>
            </div>


        </div>
    </div>
@endsection
