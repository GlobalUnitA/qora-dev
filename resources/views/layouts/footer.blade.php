
<!--div class="position-sticky mt-5" style="bottom: 79px;">
    <p class="w-100 p-3 m-0 bg-light fs-2">
        (주)글로벌유니트에이 | 대표: 김용덕 | 사업자 등록번호: 436-81-00891 | 주소: 서울 광진구 광나루로56길 85 사무동 27층 11~13호 | globalunita@globalunit-a.com
        <span class="fs-1 d-block mt-1">Copyright Global Unit-A. All rights reserved.</span>
    </p>
</div-->
<footer class="footerContainer container px-0 fixed-bottom bg-white border-start border-end border-start-sm-0 border-end-sm-0 border-top">
    <div class="container">
        <div class="row text-center py-3">
           @if(auth()->user()->profile->is_referral == 'y')
            <div class="col px-1 ">
                <a href="{{ route('register',['mid' => Auth::user()->id]) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('/images/icon/icon_menu_register.svg') }}" class="pb-1">
                    <div class="fs-3">{{ __('auth.join') }}</div>
                </a>
            </div>
            <div class="col px-1">
                <a href="#" class="text-decoration-none text-dark copyBtn" data-copy="{{ route('register', ['mid' => Auth::user()->id]) }}">
                    <img src="{{ asset('/images/icon/icon_menu_link.svg') }}" class="pb-1">
                    <div class="fs-3">{{ __('layout.referral_link') }}</div>
                </a>
            </div>
            @else
            <div class="col px-1 ">
                <span style="cursor:not-allowed">
                    <img src="{{ asset('/images/icon/icon_menu_register.svg') }}" class="pb-1" style="filter: brightness(200%) saturate(40%) opacity(60%);">
                    <div class="fs-3 text-decoration-none text-dark text-opacity-30">{{ __('auth.join') }}</div>
                </span>
            </div>
            <div class="col px-1">
                <span style="cursor:not-allowed">
                    <img src="{{ asset('/images/icon/icon_menu_link.svg') }}" class="pb-1" style="filter: brightness(200%) saturate(40%) opacity(60%);">
                    <div class="fs-3 text-decoration-none text-dark text-opacity-30">{{ __('layout.referral_link') }}</div>
                </span>
            </div>
            @endif
            <div class="col px-1">
                <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('/images/icon/icon_menu_home.svg') }}" class="pb-1">
                    <div class="fs-3">{{ __('layout.home') }}</div>
                </a>
            </div>
            <div class="col px-1">
                <a href="{{ route('trading', ['team' => true]) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('/images/icon/icon_menu_team.svg') }}" class="pb-1">
                    <div class="fs-3">{{ __('asset.team_info') }}</div>
                </a>
            </div>
            <div class="col px-1">
                <a href="{{ route('board.list', ['code' => 'terms']) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('/images/icon/icon_menu_terms.svg') }}" class="pb-1">
                    <div class="fs-3">{{ __('layout.terms') }}</div>
                </a>
            </div>
        </div>
    </div>
</footer>