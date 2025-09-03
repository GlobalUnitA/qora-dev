@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">{{ $view->board_name }}</h5>    
                    <div>{{ $view->created_at }}</div>
                </div>
                <form method="POST" action="{{ route('admin.board.update') }}" id="ajaxForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $view->id }}">
                    <hr> 
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">게시판 이름</th>
                                <td class="align-middle" colspan="3">
                                    <input type="text" name="board_name" value="{{ $view->board_name }}" class="form-control w-50">
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">게시판 코드</th>
                                <td class="align-middle">
                                    <input type="text" name="board_code" value="{{ $view->board_code }}" class="form-control w-50">
                                </td>
                                <th class="text-center align-middle">관리자 접근 레벨</th>
                                <td class="align-middle">
                                    <select name="board_level" class="form-select w-50">
                                        <option value="1" @selected($view->board_level == '1')>1</option>
                                        <option value="2" @selected($view->board_level == '2')>2</option>
                                        <option value="3" @selected($view->board_level == '3')>3</option>
                                    </select>
                                    
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle w-15">답글 여부</th>
                                <td class="align-middle">
                                        <input type="radio" name="is_comment" value="y" id="is_comment"class="form-check-input" @if($view->is_comment == 'y') checked @endif>
                                        <label class="form-check-label me-3" for="is_comment">활성</label>
                                        <input type="radio" name="is_comment" value="n" id="is_not_comment"class="form-check-input" @if($view->is_comment == 'n') checked @endif>
                                        <label class="form-check-label" for="is_not_comment">비활성</label>
                                </td>
                                <th class="text-center align-middle w-15">팝업 여부</th>
                                <td class="align-middle">
                                        <input type="radio" name="is_popup" value="y" id="is_popup"class="form-check-input" @if($view->is_popup == 'y') checked @endif>
                                        <label class="form-check-label me-3" for="is_popup">활성</label>
                                        <input type="radio" name="is_popup" value="n" id="is_not_popup"class="form-check-input" @if($view->is_popup == 'n') checked @endif>
                                        <label class="form-check-label" for="is_not_popup">비활성</label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.board.list') }}" class="btn btn-secondary">목록</a>
                        @if (auth()->guard('admin')->user()->admin_level > 3 )
                        <button type="submit" class="btn btn-danger">수정</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection