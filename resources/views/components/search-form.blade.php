<div class="card">
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            @foreach(request()->query() as $key => $value)
                @if($key !== 'start_date' && $key !== 'end_date')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <div class="row align-items-center">
                <!-- 카테고리 선택 -->
                <div class="col-12 col-md-2 mb-2">
                    <label for="search" class="sr-only">Category</label>
                    <select name="category" id="category" class="form-control" >
                        <option value="">카테고리 선택</option>
                        <option value="mid" @if(request()->category == 'mid') selected @endif>MID 조회</option>
                        <option value="account" @if(request()->category == 'account') selected @endif>아이디 조회</option>
                        <option value="name" @if(request()->category == 'name') selected @endif>이름 조회</option>
                        <option value="phone" @if(request()->category == 'phone') selected @endif>연락처 조회</option>
                    </select>
                </div>
                <!-- 텍스트 검색 -->
                <div class="col-12 col-md-2 mb-2">
                    <label for="search" class="sr-only">Keyword</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request()->get('keyword') }}">
                </div>
                <!-- 시작 날짜 -->
                <div class="col-12 col-md-3 mb-2">
                    <label for="start_date" class="sr-only">Start Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request()->get('start_date') }}">
                    </div>
                </div>
                <!-- 종료 날짜 -->
                <div class="col-12 col-md-3 mb-2">
                    <label for="end_date" class="sr-only">End Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request()->get('end_date') }}">
                    </div>
                </div>
                <!-- 검색 버튼 -->
                <div class="col-12 col-md-2 text-center mt-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>