@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">스테이킹 정책 추가</h5>
                </div>
                <form method="POST" action="{{ route('admin.staking.policy.store') }}" id="ajaxForm">
                    @csrf    
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">이름</th>
                                <td class="align-middle" colspan="3">
                                    @foreach($locale as $key => $val)
                                    <div class="d-flex mb-3">
                                        <div class="me-2" style="width: 30px;">
                                            <label class="form-label mb-0">{{ $val['code'] }} :</label>
                                        </div>
                                        <div class="col-10">
                                            <input type="text" name="translation[{{ $val['code'] }}][name]" value="" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">코인</th>
                                <td class="align-middle">
                                    <select name="coin_id" class="form-control w-50">
                                        <option value="">코인 선택</option>
                                        @foreach ($coins as $coin)
                                        <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <th class="text-center align-middle">타입</th>
                                <td class="align-middle">
                                    <input type="radio" name="staking_type" value="maturity" id="maturity" class="form-check-input">
                                    <label class="form-check-label me-3" for="maturity">원금 반환형</label>
                                    <input type="radio" name="staking_type" value="daily" id="daily" class="form-check-input">
                                    <label class="form-check-label" for="daily">원리금 지급형</label>
                                </td>
                            <tr>
                                <th class="text-center align-middle">참여수량</th>
                                <td class="align-middle" colspan="3">
                                    <div class="d-flex mb-3">
                                        <input type="text" name="min_quantity" class="form-control w-25">
                                        <div class="px-2 d-flex align-items-center">~</div>
                                        <input type="text" name="max_quantity" class="form-control w-25">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">수익률</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="daily" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">%</div>
                                </td>
                                <th class="text-center align-middle">기간</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="period" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">일</div>
                                </td>
                            </tr>
                             <tr>
                                <th class="text-center align-middle">메모</th>
                                <td class="align-middle" colspan=3>
                                    @foreach($locale as $key => $val)
                                    <div class="d-flex mb-3">
                                        <div class="me-2" style="width: 30px;">
                                            <label class="form-label mb-0">{{ $val['code'] }} :</label>
                                        </div>
                                        <div class="col-10">
                                            <textarea name="translation[{{ $val['code'] }}][memo]" class="form-control" rows="5" ></textarea>
                                        </div>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.staking.policy', ['id' => '1']) }}" class="btn btn-secondary">목록</a>
                        <button type="submit" class="btn btn-danger">추가</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('js/admin/manager/create.js') }}"></script>
@endpush