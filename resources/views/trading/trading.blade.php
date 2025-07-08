@extends('layouts.master')

@section('content')

<header class="px-4 py-5 w-100 border-top-title" style="background: url('../images/tit_bg_01.png') center right no-repeat, #1e1e1f;" >
    <h2 class="text-white mb-1 px-1">Trading</h2>
    <h6 class="text-white px-1">{{ __('asset.trading') }}</h6>
    <!-- <div class="m-0 px-1">
        <a href="{{ route('asset.withdrawal.list') }}">
            <h5 class="btn btn-outline-light m-0">출금내역</h5>
        </a>
    </div> -->
</header>
<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">
        <fieldset>
            <legend class="fs-4 text-body mb-3">{{ __('asset.select_trading_asset_guide') }}</legend>
            <div class="d-grid d-grid-col-2 mb-3">
                @foreach($assets as $asset)
                <div>
                    <input type="radio" class="btn-check" name="id" id="{{ $asset->coin->code }}" autocomplete="off" onclick="location.href='{{ route('trading', ['asset' => $asset->encrypted_id]) }}';">
                    <label class="btn btn-light w-100 p-4 rounded text-center fs-5 d-flex flex-column align-items-center @if(optional($selected_asset)->encrypted_id == $asset->encrypted_id) active @endif" for="{{ $asset->coin->code }}">
                        <img src="{{ $asset->coin->image_urls[0] }}" width="40" alt="{{ $asset->coin->code }}" class="img-fluid mb-2">
                        {{ $asset->coin->name }}
                    </label>
                </div>
                @endforeach
            </div>                
        </fieldset>
        @isset($data)
        <ul class="nav nav-underline mt-5 mb-3 fs-6" id="trading-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link @if(!request()->has('team')) active @endif" id="trading-mypage-tab" data-bs-toggle="pill" data-bs-target="#trading-mypage" type="button" role="tab" aria-controls="trading-mypage" aria-selected="true">{{ __('asset.my_info') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if(request()->has('team')) active @endif" id="trading-myteam-tab" data-bs-toggle="pill" data-bs-target="#trading-myteam" type="button" role="tab" aria-controls="trading-myteam" aria-selected="false">{{ __('asset.team_info') }}</button>
            </li>
        </ul>
        <div class="tab-content" id="trading-tabContent">
            <div class="tab-pane fade show @if(!request()->has('team')) active @endif" id="trading-mypage" role="tabpanel" aria-labelledby="trading-mypage-tab" tabindex="0">
                <p class="py-3 fs-4">
                    {{ __('user.level') }}<span class="text-body fw-semibold ps-2 d-inline-block">{{ $data['grade'] }}</span>
                </p>
                <div class="p-4 rounded bg-light text-body mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.total_asset') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['balance'] }}</h3>
                        </div>                        
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.total_profit') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['profit']['total'] }}</h3>
                        </div>                        
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.today_profit') }}</p>
                            <h3 class="text-body fs-6 mb-1">{{ $data['profit']['today'] }}</h3>
                        </div>                                          
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.yesterday_profit') }}</p>
                            <h3 class="text-body fs-6 mb-1">{{ $data['profit']['yesterday'] }}</h3>
                        </div>  
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.today_profit_rate') }}</p>
                            <h3 class="text-body fs-6 mb-1">{{ $profit_rate }}%</h3>
                        </div>     
                    </div>
                </div>                
                <div class="text-center">
                    <img src="{{ asset('images/robot.png') }}" width="300" alt="" class="img-fluid rounded-2">
                    <!--p>{{ __('asset.trading_count') }}: ( {{ $data['current_count'] }} / {{ $data['max_count'] }} )</p>
                    <form method="GET" action="{{ route('trading.wait') }}" id="tradingForm">
                        <input type="hidden" name="coin" value="{{ $selected_asset->coin_id }}">
                        <input type="hidden" id="maxCount" value="{{ $data['max_count'] }}">
                        <input type="hidden" id="currentCount" value="{{ $data['current_count'] }}">
                        <input type="hidden" id="balance" value="{{ $selected_asset->balance }}">
                        @if($is_valid)
                        <input type="hidden" id="is_valid" value="1">
                        @endif
                        @if($available_day)
                        <input type="hidden" id="available_day" value="1">
                        @endif
                        <button type="submit" class="btn btn-primary w-100 py-3 fs-4 my-3">{{ __('asset.trading_participate') }}</button>
                    </form>
                    <p>{{ __('asset.trading_policy_guide') }}</p-->
                </div>
            </div>
            <div class="tab-pane fade show @if(request()->has('team')) active @endif" id="trading-myteam" role="tabpanel" aria-labelledby="trading-myteam-tab" tabindex="0">
                <div class="p-4 rounded bg-light text-body mb-4 mt-5">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.referral_count') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['direct_count'] }}</h3>
                        </div>                        
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.child_count') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['referral_count'] }}</h3>
                        </div>                                          
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.total_group_sales') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['group_sales'] }}</h3>
                        </div>                        
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.total_subscription_bonus') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['bonus']['total'] }}</h3>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <p class="text-body fs-4 m-0">{{ __('asset.group_sales_expected') }}</p>
                            <h3 class="text-primary fs-6 mb-1">{{ $data['group_sales_expected'] }}</h3>
                        </div>
                    </div>                                     
                </div>
                @if($subscription_bonuses->isNotEmpty())                
                <div class="table-responsive overflow-x-auto mt-5">
                    <table class="table table-striped table-bordered break-keep-all">
                        <thead>
                            <tr>
                                <th>{{ __('system.date') }}</th>
                                <th>{{ __('auth.login_id') }}</th>
                                <th>{{ __('asset.subscription_bonus') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($subscription_bonuses->take(5) as $bonus)
                        <tr>
                            <td>{{ date_format($bonus->created_at, 'Y-m-d h:i:s') }}</td>
                            <td>C{{ $bonus->referrer_id }}</td>
                            <td>{{ number_format(floor( $bonus->bonus * 10000) / 10000, 4) }} {{ $bonus->transfer->income->coin->code }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if($subscription_bonuses->count() > 5)
                    <a href="{{ route('trading.list') }}" class="btn btn-outline-primary w-100 py-2 my-4 fs-4">{{ __('system.load_more') }}</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif     
    </div>
</main>
@endsection

@push('message')
<div id="msg_trading_disabled_user" data-label="{{ __('asset.lack_balance_notice') }}"></div>
<div id="msg_trading_disabled_day" data-label="{{ __('asset.trading_disabled_day_notice') }}"></div>
<div id="msg_check_asset" data-label="{{ __('asset.asset_check_notice') }}"></div>
<div id="msg_trading_limit" data-label="{{ __('asset.trading_limit_notice') }}"></div>
@endpush

@push('script')
<script src="{{ asset('js/trading/trading.js') }}"></script>
@endpush
