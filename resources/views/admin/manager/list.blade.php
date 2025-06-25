@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!--div class="d-flex justify-content-between">
                    <ul class="nav nav-tabs mt-3" id="tableTabs" role="tablist" >
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('admin.manager.list') }}" class="nav-link active">목록</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href=""  class="nav-link" >권한</a>
                        </li>
                    </ul>
                </div-->
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <h5 class="card-title">관리자 목록</h5>
                            <a href="{{ route('admin.manager.export') }}" class="btn btn-primary">Excel</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap align-middle mb-0 table-striped table-hover">
                                <thead>
                                    <tr class="border-2 border-bottom border-primary border-0"> 
                                        <th scope="col" class="text-center">번호</th>
                                        <th scope="col" class="text-center">ID</th>
                                        <th scope="col" class="text-center">이름</th>
                                        <th scope="col" class="text-center">레벨</th>
                                        <th scope="col" class="text-center">일자</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @if($list->isNotEmpty())
                                    @foreach ($list as $key => $value)
                                    <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.manager.view', ['id' => $value->id]) }}';">
                                        <td scope="col" class="text-center">{{ $list->firstItem() + $key }}</td>
                                        <td scope="col" class="text-center">{{ $value->account }}</td>
                                        <td scope="col" class="text-center">{{ $value->name }}</td>
                                        <td scope="col" class="text-center">{{ $value->admin_level }}</td>
                                        <td scope="col" class="text-center">{{ $value->created_at }}</td>
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
                        <div class="d-flex justify-content-center mt-5">
                            {{ $list->links('pagination::bootstrap-5') }}
                        </div>
                        <hr>
                        <div class="mb-3 d-flex justify-content-end">
                            <a href="{{ route('admin.manager.create') }}" class="btn btn-primary">관리자 추가</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection