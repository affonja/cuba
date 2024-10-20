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

        {{--        tab import--}}
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
             tabindex="0">
            <form class="mt-5 mb-5" action="/" method="POST" id="formImport">
                @csrf
                <div class="row">
                    <label for="titleWord" class="form-label">Заголовок статьи</label>
                </div>
                <div class="row">
                    <div class="col-3">
                        <input type="text" class="form-control" id="titleWord" aria-describedby="titleWord"
                               placeholder="" name="titleWord">
                        <div class="feedback"></div>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary" id="btnImport">Импорт</button>
                    </div>
                    <div class="col-3">
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0"
                             aria-valuemin="0" aria-valuemax="100">
                            <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
                {{--                <div class="row">--}}
                {{--                <div id="titleWord" class="form-text">Поиск по ключевому слову</div>--}}
                {{--                </div>--}}
            </form>

            <div class="row">
                <div class="statusImport col-12 d-none">
                </div>
            </div>


            <div class="row">
                <hr>
            </div>

            <div class="row">
                <div class="col-12 mt-3">
                    @include('components.articles_table')
                </div>
            </div>
        </div>


        {{--tab search --}}
        <div class="tab-pane fade" id="nav-search" role="tabpanel" aria-labelledby="nav-search-tab" tabindex="0">
            <form class="mt-5 mb-5" action="/" method="POST">
                @csrf
                <div class="row">
                    <div class="col-3">
                        <input type="text" class="form-control" id="keyWord" aria-describedby="searchWord"
                               name="keyWord">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary" id="btnSearch">Найти</button>
                    </div>
                </div>
            </form>

            <div class="row">
                <hr>
            </div>

            <div class="row">
                <div class="resultSearch mt-3 mb-5 d-none"></div>
            </div>

            <div class="row">
                <div class="resultTable col-6 d-none">
                </div>

                <div class="articlePreview col-6 d-none border-2">
                </div>
            </div>


        </div>


    </div>
@endsection
