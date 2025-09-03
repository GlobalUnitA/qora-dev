@extends('layouts.master')

@section('content')
<div class="container py-5">
    @if(request()->route('mode') == 'ref')
    <h2 class="mb-3 text-center">{{ __('messages.purchase.ref_bonus_history') }}</h2>
    @else
    <h2 class="mb-3 text-center">{{ __('messages.purchase.aff_bonus_history') }}</h2>
    @endif
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('messages.layout.date') }}</th>
                    <th>{{ __('messages.layout.price') }}</th>
                    @if(request()->route('mode') == 'ref')
                    <th>{{ __('messages.purchase.ref_id') }}</th>
                    @else
                    <th>{{ __('messages.purchase.aff_id') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $key => $value)
                <tr>
                    <td>{{ $value->created_at }}</td>
                    <td>{{ $value->bonus }}</td>
                    <td>{{ $value->aff_user_id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $list->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
