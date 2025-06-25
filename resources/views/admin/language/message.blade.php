@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" id="ajaxForm"  action="{{ route('admin.language.update') }}" >
                    @csrf
                    <input type="hidden" name="mode" value="message" />
                    <input type="hidden" name="category" value="{{ $selected_category }}" />
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">메시지 설정</h5>
                    </div> 
                    <hr>
                <ul class="nav nav-tabs mt-5" id="tableTabs" role="tablist" >
                    @foreach($category_list as $key => $val)
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('admin.language', ['mode' => 'message', 'category' => $val]) }}" class="nav-link {{ $selected_category === $val  ? 'active' : '' }}">{{ $val }}</a>
                    </li>
                    @endforeach
                </ul>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>   
                                <td id="input_message" class="align-middle">
                                    @foreach($message_key as $key => $val)
                                    <div class="row align-items-center mb-2 add_message">
                                        <div class="col-auto">
                                            <label class="form-label mb-0">Id:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" value="{{ $val->key }}" class="form-control form-control-sm" readonly/>
                                        </div>
                                        <div class="col-auto">
                                            <label class="form-label mb-0">설명:</label>
                                        </div>
                                        <div class="col-5">
                                            <input type="text" value="{{ $val->description }}" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger btn-sm removeMessageBtn" data-key="{{ $val->key }}">- 삭제</button>
                                        </div>
                                    </div>
                                    @endforeach
                                    <hr>
                                    <div class="row align-items-center mb-2 add_message">
                                        <div class="col-auto">
                                            <label class="form-label mb-0">Id:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="key[]" value="" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-auto">
                                            <label class="form-label mb-0">설명:</label>
                                        </div>
                                        <div class="col-5">
                                            <input type="text" name="description[]" value="" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-success btn-sm addMessageBtn" data-page="{{ $selected_category }}">+ 추가</button>
                                        </div>
                                    </div>
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
<form method="POST" id="messageForm" action="{{ route('admin.language.delete') }}" >
    @csrf
    <input type="hidden" name="category" value="{{ $selected_category }}" />
    <input type="hidden" name="key" value="" />
</form>
@endsection

@push('script')
<script src="{{ asset('js/admin/language.js') }}"></script>
@endpush