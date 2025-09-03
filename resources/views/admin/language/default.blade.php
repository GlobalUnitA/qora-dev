@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" id="ajaxForm" action="{{ route('admin.language.update') }}" >
                    @csrf
                    <input type="hidden" name="mode" value="default">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">기본 설정</h5>
                    </div> 
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">지원 언어</th>
                                <td id="input_language" class="align-middle">
                                    @isset($locale)
                                    @foreach($locale as $key => $val)
                                    <div class="row align-items-center mb-2 add_language">
                                        <div class="col-auto">
                                            <label class="form-label mb-0">코드:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="content[locale][{{ $key }}][code]" value="{{ $val['code'] }}" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-auto">
                                            <label class="form-label mb-0">언어:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="content[locale][{{ $key }}][name]" value="{{ $val['name'] }}" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" id="removeLanguageBtn" class="btn btn-danger btn-sm">- 삭제</button>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    <hr>
                                    <div class="row align-items-center mb-2 add_language">
                                        <div class="col-auto">
                                            <label class="form-label mb-0">코드:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="content[locale][{{ count($locale ?? []) }}][code]" value="" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-auto">
                                            <label class="form-label mb-0">언어:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="content[locale][{{ count($locale ?? []) }}][name]" value="" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" id="addLanguageBtn" class="btn btn-success btn-sm">+ 추가</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">카테고리</th>
                                <td id="input_page" class="align-middle">
                                    @isset($category)
                                    @foreach($category as $key => $val)
                                    <div class="row align-items-center mb-2 add_page">
                                        <div class="col-auto">
                                            <label class="form-label mb-0">카테고리:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="content[category][]" value="{{ $val }}" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" id="removePageBtn" class="btn btn-danger btn-sm">- 삭제</button>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    <hr>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-auto">
                                            <label class="form-label mb-0">카테고리:</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="content[category][]" value="" class="form-control form-control-sm"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" id="addPageBtn" class="btn btn-success btn-sm">+ 추가</button>
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

@endsection

@push('script')
<script src="{{ asset('js/admin/language.js') }}"></script>
@endpush