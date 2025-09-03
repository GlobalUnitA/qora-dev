@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">등급</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0 table-striped">
                        <thead>
                            <tr class="border-2 border-bottom border-primary border-0"> 
                                <th scope="col" class="ps-0 text-center">번호</th>
                                <th scope="col" class="text-center">이름</th>
                                <th scope="col" class="text-center">레벨</th>
                                <th scope="col" class="text-center">설명</th>
                                <th scope="col" class="text-center">일자</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @if($list->isNotEmpty())
                                @foreach ($list as $key => $value)
                                <tr>
                                    <th scope="row" class="ps-0 fw-medium text-center">{{ $list->firstItem() + $key }}</th>
                                    <td class="text-center">{{ $value->name }}</td>
                                    <td class="text-center">{{ $value->level }}</td>
                                    <td class="text-center">{{ $value->description }}</td>
                                    <td class="text-center">{{ $value->created_at }}</td>
                                    <td><button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $value->id }}">삭제</button></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <hr>
                </div>
                <div class="d-flex justify-content-center mt-5">
                    {{ $list->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">등급 추가</h5>
                </div>
                <form method="POST" action="{{ route('admin.user.grade.store') }}" id="ajaxForm" data-confirm-message="등급을 추가하시겠습니까?" >
                    @csrf    
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">이름</th>
                                <td class="align-middle">
                                    <input type="text" name="name" value="" class="form-control w-50">
                                </td>
                                <th class="text-center align-middle">레벨</th>
                                <td class="align-middle">
                                    <input type="text" name="level" value="" class="form-control w-50">
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">설명</th>
                                <td colspan="3" class="align-middle">
                                    <input type="text" name="description" value="" class="form-control w-75">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-danger">추가</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.user.grade.delete') }}" id="deleteForm">
    @csrf
</form>

@endsection

@push('script')
<script src="{{ asset('js/admin/user/grade.js') }}"></script>
@endpush