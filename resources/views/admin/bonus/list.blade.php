@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('components.search-form', ['route' => route('admin.bonus.list')])
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">보너스 지급 목록</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0 table-striped table-hover">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0"> 
                                    <th scope="col" class="ps-0 text-center">번호</th>
                                    <th scope="col" class="text-center">UID</th>
                                    <th scope="col" class="text-center">회원명</th>
                                    <th scope="col" class="text-center">보너스</th>
                                    <th scope="col" class="text-center">누적 보너스</th>
                                    <th scope="col" class="text-center">지급일자</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if($list->isNotEmpty())
                                @foreach ($list as $key => $value)
                                <tr>
                                    <th scope="row" class="ps-0 fw-medium text-center">{{ $list->firstItem() + $key }}</th>
                                    <td class="text-center">C{{ $value->user_id }}</td>
                                    <td class="text-center">{{ $value->user->name }}</td>
                                    <td class="text-center">{{ number_format($value->bonus) }}</td>
                                    <td class="text-center">{{ number_format($value->user->profile->total_bonus) }}</td>
                                    <td class="text-center">{{ $value->created_at }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr> 
                                    <td colspan="5" class="text-center">No Data.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        {{ $list->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
