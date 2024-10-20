<form class="mt-5 mb-5" action="/" method="POST" id="formImport">
    @csrf
    <div class="row">
        <label for="titleWord" class="form-label">{{ __('Заголовок статьи') }}</label>
    </div>

    <div class="row">

        <div class="col-3">
            <input type="text" class="form-control" id="titleWord" aria-describedby="titleWord"
                   placeholder="" name="titleWord">
            <div class="feedback"></div>
        </div>

        <div class="col-2">
            <button type="submit" class="btn btn-primary" id="btnImport">{{ __('Импорт') }}</button>
        </div>

        <div class="col-3">
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0"
                 aria-valuemin="0" aria-valuemax="100">
                <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
            </div>
        </div>

    </div>

</form>
