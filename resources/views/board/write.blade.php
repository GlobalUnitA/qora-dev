@extends('layouts.master')

@section('content')
<main class="container-fluid py-5 mb-5">
    @if($mode == 'write')
    <form method="POST" id="boardForm" action="{{ route('board.write') }}" >
    @else
    <form method="POST" id="boardForm" action="{{ route('board.modify') }}" >
    @endif
        @csrf
        <input type="hidden" name="board_id" value="{{ $board->id }}">
        @if($mode == 'modify')
        <input type="hidden" name="post_id" value="{{ $view->id }}">
        @endif
        <div class="mb-4">
            <h5 class="card-title">
                @if($mode == 'write')
                {{ $board->locale_name }}
                @else
                {{ $board->locale_name }} 
                @endif
            </h5>    
        </div>
        <div class="mb-4">
            <label for="subject" class="form-label fw-bold">{{ __('system.title') }}</label>
            <input type="text" class="form-control" id="subject" name="subject" value="{{ $view->subject ?? '' }}" >
        </div>

        <div class="mb-4">
            <label for="content" class="form-label fw-bold">{{ __('system.contents') }}</label>
            <div id="editor" data-content="{{ $view->content ?? '' }}"></div>
            <textarea name="content" id="content" class="d-none"></textarea>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-end align-items-center">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <a href="{{ route('board.list', ['code' => $board->board_code ])}}" class="btn btn-secondary">{{ __('system.list') }}</a>
                    @if($mode == 'write')
                    <button type="submit" class="btn btn-inverse">{{ __('layout.submit_request') }}</button>
                    @else
                    <button type="submit" class="btn btn-info">{{ __('system.modify') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</main>
@endsection

@push('message')
<div id="msg_input_title" data-label="{{ __('layout.input_title_notice') }}"></div>
<div id="msg_input_contents" data-label="{{ __('layout.input_contents_notice') }}"></div>
@endpush

@push('script')
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
<script src="{{ asset('js/board.js') }}"></script>
@endpush