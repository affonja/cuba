<form class="mt-5 mb-5" action="/" method="POST">
    @csrf
    <div class="row">
        <div class="col-3">
            <input type="text" class="form-control" id="keyWord" aria-describedby="searchWord" name="keyWord">
        </div>
        <div class="col-2">
            <button type="submit" class="btn btn-primary" id="btnSearch">{{ __('Найти') }}</button>
        </div>
    </div>
</form>
