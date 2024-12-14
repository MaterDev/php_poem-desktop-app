@extends('layouts.app')

@section('content')
    <div id="menu-bar" class="menu-bar">
        <img class="apple-menu" src="{{ url('/resources/images/logo.png') }}" alt="Apple Menu">
        <span>File</span>
    </div>

    <div class="desktop">
        <!-- Desktop Icons -->
        @foreach($poems as $poem)
        @include('components.desktop-icon', ['poem' => $poem])
        @endforeach

        <!-- Windows (hidden by default) -->
        @foreach($poems as $poem)
        @include('components.poem-window', ['poem' => $poem])
        @endforeach
    </div>
@endsection