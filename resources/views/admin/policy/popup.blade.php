@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" id="popupForm" action="{{ route('admin.policy.update') }}" >
                    @csrf
                    <input type="hidden" name="type" value="popup_policy">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">팝업 관리</h5>
                    </div> 
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">팝업 기간</th>
                                <td class="d-flex align-middle">
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="start_date" class="sr-only">Start Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                            <input type="date" name="content[start_date]" id="start_date" class="form-control" value="{{ $start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 ms-4 mb-2">
                                        <label for="end_date" class="sr-only">End Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                            <input type="date" name="content[end_date]" id="end_date" class="form-control" value="{{ $end_date }}">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">팝업 제목</th>
                                <td class="d-flex align-middle">
                                    <input name="content[head]" class="form-control" value="{{ $head }}">
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">팝업 내용</th>
                                <td class="d-flex align-middle">
                                <div id="summernote">{!! $body !!}</div>
                                <input type="hidden" name="content[body]" id="contentBody">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-end align-items-center">    
                        <button type="submit" class="btn btn-danger">저장</button>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-lite.min.js"></script>
<script src="{{ asset('js/admin/popup.js') }}"></script>
@endpush