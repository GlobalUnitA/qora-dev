@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">KYC 인증</h5>    
                    <div>{{ $view->created_at }}</div>
                </div>
                <form method="POST" action="{{ route('admin.user.kyc.update') }}" id="ajaxForm" >
                    @csrf
                    <input type="hidden" name="id" value="{{ $view->id }}">
                    <input type="hidden" name="user_id" value="{{ $view->user->id }}">
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">이름</th>
                                <td class="align-middle">{{ $view->user->name }}</td>
                                <th class="text-center align-middle">아이디</th>
                                <td class="align-middle">{{ $view->user->account }}</td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">타입</th>
                                <td class="align-middle">{{ $view->type_text }}</td>
                                <th class="text-center align-middle">상태</th>
                                <td class="align-middle">
                                    @if($view->status == 'pending')
                                    <select name="status" id="category" class="form-select w-50">
                                        <option value="pending" @if($view->status == 'pending') selected @endif>신청</option>
                                        <option value="approved" @if($view->status == 'approved') selected @endif>통과</option>
                                        <option value="rejected" @if($view->status == 'rejected') selected @endif>미통과</option>
                                    </select>
                                    @else
                                    {{ $view->status_text }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">Given Name</th>
                                <td class="align-middle">{{ $view->given_name }}</td>
                                <th class="text-center align-middle">Surname</th>
                                <td class="align-middle">{{ $view->surname }}</td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">신분증 번호</th>
                                <td class="align-middle">{{ $view->id_number }}</td>
                                <th class="text-center align-middle">Date of Birth</th>
                                <td class="align-middle">{{ date_format($view->date_of_birth, 'Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">이미지</th>
                                <td colspan=3 class="align-middle">
                                    <div class="text-center align-middle">
                                        @if($view->image_urls)
                                            @foreach($view->image_urls as $val)
                                                <a href="{{ $val }}" class="me-5">
                                                    <img src="{{ $val }}" class="img-fluid" style="height:300px">
                                                </a>
                                            @endforeach
                                        @else
                                            <span>이미지가 없습니다.</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">메모</th>
                                <td colspan=3 class="align-middle">
                                    <textarea name="memo" class="form-control" id="memo" rows="12" >{{ $view->memo }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.user.kyc.list') }}" class="btn btn-secondary">목록</a>
                        <button type="submit" class="btn btn-danger">수정</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/admin/user/view.js') }}"></script>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="{{ asset('js/postcode.js') }}"></script>
@endpush