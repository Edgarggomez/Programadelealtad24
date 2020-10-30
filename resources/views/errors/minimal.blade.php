@extends('layouts.app')

@section('content')
<div class="flex-center position-ref full-height">
    <div class="code">
        @yield('code')
    </div>

    <div class="message" style="padding: 10px;">
        @yield('message')
    </div>
</div>
<style>
    .full-height {
        height: 70vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .code {
        border-right: 2px solid;
        font-size: 56px;
        padding: 0 15px 0 15px;
        text-align: center;
    }

    .message {
        font-size: 28px;
        text-align: center;
    }
</style>
@endsection
