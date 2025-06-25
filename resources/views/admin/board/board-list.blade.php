@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <h5 class="card-title">게시판</h5>
                        </div>
                        <form method="post" action="" id="ajaxForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table text-nowrap align-middle mb-0 table-striped table-hover">
                                    <thead>
                                        <tr class="border-2 border-bottom border-primary border-0"> 
                                            <!--th scope="col" class="ps-0 text-center"></th-->
                                            <th scope="col" class="text-center">번호</th>
                                            <th scope="col" class="text-center">게시판 코드</th>
                                            <th scope="col" class="text-center">게시판 이름</th>
                                            <th scope="col" class="text-center">답글 여부</th>
                                            <th scope="col" class="text-center">팝업 여부</th>
                                            <th scope="col" class="text-center">배너 여부</th>
                                            <th scope="col" class="text-center">일자</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        @if($list->isNotEmpty())
                                        @foreach ($list as $key => $value)
                                        <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.post.list', ['code' => $value->board_code]) }}';">
                                            <!--td scope="row" class="ps-0 fw-medium text-center"><input type="checkbox" name="check[]" value="{{ $value->id }}" class="form-check-input" /></td-->
                                            <td class="text-center">{{ $list->firstItem() + $key }}</td>
                                            <td class="text-center">{{ $value->board_code }}</td>
                                            <td class="text-center">{{ $value->board_name }}</td>
                                            <td class="text-center">{{ $value->is_comment }}</td>
                                            <td class="text-center">{{ $value->is_popup }}</td>
                                            <td class="text-center">{{ $value->is_banner }}</td>
                                            <td class="text-center">{{ $value->created_at }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="7">No Data.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!--div class="d-flex mt-5">     
                                <button type="submit" id="postDeletebtn" class="btn btn-danger ms-auto">게시글 삭제</button>
                            </div-->
                        </form>
                        <div class="d-flex justify-content-center mt-5">
                            {{ $list->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection