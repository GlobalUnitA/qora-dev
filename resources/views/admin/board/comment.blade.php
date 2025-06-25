@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">게시글</h5>    
                    <a href="{{ route('admin.post.list', ['code' => $board->board_code ]) }}" class="btn btn-secondary">목록</a>
                </div>
                <hr>
                <table class="table table-bordered mt-5 mb-5">
                    <tbody>
                        <tr>
                            <th class="text-center align-middle">게시판</th>
                            <td class="align-middle">{{ $board->board_name }}</td>
                            <th class="text-center align-middle">아이디</th>
                            <td class="align-middle">
                                @if ($view->admin)
                                    {{ $view->admin->account }} <span class="badge bg-danger">관리자</span>
                                @elseif ($view->user)
                                    {{ $view->user->account }}
                                @else
                                    -
                                @endif
                            </td>
                            <th class="text-center align-middle w-15">이름</th>
                            <td class="align-middle w-20">
                                @if ($view->admin)
                                    {{ $view->admin->name }}
                                @elseif ($view->user)
                                    {{ $view->user->name }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">제목</th>
                            <td colspan=5 class="align-middle">{{ $view->subject }}</td>
                        </tr>
                        <tr>
                            <td colspan=6 class="align-middle">{!! $view->content !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>  
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">답글</h5>    
                </div>
                @if(!$comments->isEmpty())
                <hr>
                <div class="list-group">
                    @foreach($comments as $comment)
                    <div class="list-group-item">
                        <i class="ti ti-corner-down-right"></i>    
                        <strong>{{ $comment->user ? $comment->user->name : $comment->admin->name }}</strong>
                        @if($comment->admin)
                            <span class="badge bg-danger">관리자</span>
                        @endif
                        <div id="comment_{{ $comment->id }}" class="mt-2 ms-4">
                            <p class="comment-content">{!! $comment->content !!}</p>
                            <textarea class="form-control comment-edit d-none">{!! $comment->content !!}</textarea>
                            <small class="comment-date">{{ $comment->created_at->format('Y-m-d h:i:s') }}</small>
                            @if($comment->admin && $comment->admin->id === Auth::guard('admin')->id())
                                <div class="mt-1">
                                    <small class="cursor-pointer text-primary editBtn" data-comment="{{ $comment->id }}">수정</small>
                                    <small class="cursor-pointer text-success saveBtn d-none" data-comment="{{ $comment->id }}">저장</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                <form method="post" action="{{ route('admin.post.comment') }}" id="ajaxForm">
                    @csrf
                    <input type="hidden" name="board_id" value="{{ $board->id }}">
                    <input type="hidden" name="post_id" value="{{ $view->id }}">
                    <div class="d-flex align-items-center gap-2 mt-5">
                        <textarea name="content" class="form-control flex-grow-1 w-75" id="content" rows="3" placeholder="댓글을 입력하세요..."></textarea>
                        <button type="submit" class="btn btn-info h-100">작성</button>
                    </div>
                    <hr>
                </form>
            </div>
        </div>        
    </div>
</div>

<form method="post" action="{{ route('admin.post.comment.update') }}" id="commentForm">
    @csrf
</form>
@endsection

@push('script')
<script src="{{ asset('js/admin/board/comment.js') }}"></script>
@endpush