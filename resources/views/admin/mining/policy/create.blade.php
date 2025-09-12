@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">마이닝 정책 추가</h5>
                </div>
                <form method="POST" action="{{ route('admin.mining.policy.store') }}" id="ajaxForm">
                    @csrf
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 35%;">
                            <col style="width: 15%;">
                            <col style="width: 35%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">참여 코인</th>
                                <td class="align-middle">
                                    <select name="coin_id" class="form-select w-50">
                                        <option value="">코인 선택</option>
                                        @foreach ($coins as $coin)
                                        <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <th class="text-center align-middle">기간</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="period" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">일</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">원금 코인</th>
                                <td class="align-middle">
                                    <select name="refund_coin_id" class="form-select w-50">
                                        <option value="">코인 선택</option>
                                        @foreach ($coins as $coin)
                                            <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <th class="text-center align-middle">수익 코인</th>
                                <td class="align-middle">
                                    <select name="reward_coin_id" class="form-select w-50">
                                        <option value="">코인 선택</option>0
                                        @foreach ($coins as $coin)
                                        <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">즉시 지급</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="instant_rate" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">%</div>
                                </td>
                                <th class="text-center align-middle">분할 지급</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="split_rate" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">%</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">채굴량</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="node_reward" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">1 노드 당 채굴량(1일)</div>
                                </td>
                                <th class="text-center align-middle">환율</th>
                                <td class="align-middle d-flex">
                                    <input type="text" name="exchange_rate" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">%</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">최대 노드 수량</th>
                                <td class="align-middle d-flex" colspan="3">
                                    <input type="text" name="node_limit" class="form-control w-25">
                                    <div class="px-2 d-flex align-items-center">개</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered mt-5 mb-5">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 85%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">이름</th>
                                <td class="align-middle">
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
                                <th class="text-center align-middle">메모</th>
                                <td class="align-middle">
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
                        <a href="{{ route('admin.mining.policy') }}" class="btn btn-secondary">목록</a>
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
