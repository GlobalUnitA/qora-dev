@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">월렛 정책</h5>
                    <a href="{{ route('admin.wallet.policy.export') }}" class="btn btn-primary">Excel</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0 table-striped">
                        <thead>
                            <tr class="border-2 border-bottom border-primary border-0"> 
                                <th scope="col" class="ps-0 text-center">참여 수량</th>
                                <th scope="col" class="text-center">수익률</th>
                                <th scope="col" class="text-center">입금 수수료</th>
                                <th scope="col" class="text-center">출금 수수료</th>
                                <th scope="col" class="text-center" >수정일자</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach($policies as $key => $val)
                            <tr class="wallet_policy">
                                <input type="hidden" name="id" value="{{ $val->id }}" >
                                <td class="text-center"><input type="text" name="min_quantity" value="{{ $val->min_quantity }}" class="form-control"></td>
                                <td class="text-center"><input type="text" name="profit_rate" value="{{ $val->profit_rate }}" class="form-control"></td>
                                <td class="text-center"><input type="text" name="deposit_fee_rate" value="{{ $val->deposit_fee_rate }}" class="form-control"></td>
                                <td class="text-center"><input type="text" name="withdrawal_fee_rate" value="{{ $val->withdrawal_fee_rate }}" class="form-control"></td>
                                <td class="text-center">{{ $val->updated_at }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger updateBtn">수정</button>
                                </td>
                            </tr>                    
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
        </div>

        @if($modify_logs->isNotEmpty())
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">정책 변경 로그</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0 table-striped">
                        <thead>
                            <tr class="border-2 border-bottom border-primary border-0"> 
                                <th scope="col" class="ps-0 text-center">변경 내용</th>
                                <th scope="col" class="ps-0 text-center">변경 전</th>
                                <th scope="col" class="ps-0 text-center">변경 후</th>
                                <th scope="col" class="ps-0 text-center">관리자</th>
                                <th scope="col" class="ps-0 text-center">수정일자</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach($modify_logs as $key => $val)
                            <tr>
                                <td class="text-center">{{ $val->column_description }}</td>
                                <td class="text-center">{{ $val->old_value }}</td>
                                <td class="text-center">{{ $val->new_value }}</td>
                                <td class="text-center">{{ $val->name }}</td>
                                <td class="text-center">{{ $val->created_at }}</td>
                            </tr>                    
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<form method="POST" id="walletPolicyForm" action="{{ route('admin.wallet.policy.update') }}" >
    @csrf
</form>

@endsection

@push('script')
<script src="{{ asset('js/admin/wallet/policy.js') }}"></script>
@endpush