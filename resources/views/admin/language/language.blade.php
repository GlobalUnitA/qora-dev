@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card full-card" style="margin-left: -300px; margin-right: -300px; width: calc(100% + 600px);">
        <form method="POST" id="ajaxForm" action="{{ route('admin.language.update') }}">
        @csrf
        <input type="hidden" name="mode" value="language">
        <input type="hidden" name="category" value="{{ $selected_category }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">언어 설정</h5>
                </div> 
                <hr>
                <ul class="nav nav-tabs mt-5" id="tableTabs" role="tablist" >
                    @foreach($category_list as $key => $val)
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('admin.language', ['mode' => 'language', 'category' => $val]) }}" class="nav-link {{ $selected_category === $val  ? 'active' : '' }}">{{ $val }}</a>
                    </li>
                    @endforeach
                </ul>
                    <table class="table table-bordered mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center">메시지 키</th>
                                @foreach ($locales as $locale)
                                <th class="text-center">{{ $locale['name'] }}</th>
                                @endforeach
                            </tr>
                            @if(!empty($message))
                            @foreach($message as $key => $val)
                            <tr>
                                <td>
                                    <p>{{ $val['key'] }}</p>
                                    <p>({{ $val['desc'] }})</p>
                                </td>
                                @foreach($val['value'] as $k => $v)
                                <td class="align-middle">
                                    <textarea name="lang[{{ $val['key'] }}][{{ $k }}]" class="form-control" rows="4">{{ $v }}</textarea>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="text-center">메시지 키가 존재하지 않습니다.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-end align-items-center">    
                        <button type="submit" class="btn btn-danger">저장</button>
                    </div> 
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('js/admin/language.js') }}"></script>
@endpush