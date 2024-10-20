@extends('layouts.app')
@section('content')
    @include('components.nav')
    <div class="tab-content" id="nav-tabContent">
        @include('components.tab_import')
        @include('components.tab_search')
    </div>
@endsection
