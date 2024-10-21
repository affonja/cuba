<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
    @include('components.form_import')

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
    <div class="row">
        <div class="col-12 mt-3">
            {{ $articles->links() }}
        </div>
    </div>
</div>
