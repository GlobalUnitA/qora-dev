<?php

namespace App\Http\Controllers\Admin\User;


use App\Exports\KycExport;
use App\Models\UserProfile;
use App\Models\KycVerification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class KycVerificationController extends Controller
{
    public function list(Request $request)
    {
        $list = KycVerification::all();

        $list = KycVerification::when($request->filled('category') && $request->filled('keyword'), function ($query) use ($request) {
            switch ($request->category) {
                case 'mid':
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('users.id', $request->keyword);
                    });
                    break;
                case 'account':
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('users.account', $request->keyword);
                    });
                    break;
                case 'name':
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('users.name', $request->keyword);
                    });
                    break;
                case 'phone':
                    $query->whereHas('userProfile', function ($query) use ($request) {
                        $query->where('user_profiles.phone', $request->keyword);
                    });
                    break;
            }
        })
        ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
            $query->whereBetween('asset_transfers.created_at', [
                $request->start_date,
                $request->end_date
            ]);
        })
        ->latest()
        ->paginate(10);

        return view('admin.user.kyc-list', compact('list'));
    }

    public function view($id)
    {
   
        $view = KycVerification::find($id);
        
        if (!$view) {
            abort(404, '404 not found');
        }

        return view('admin.user.kyc-view', compact('view'));
    }

    public function update(Request $request)
    {
   
        $kyc = KycVerification::find($request->id);


        if ($kyc) {

            DB::beginTransaction();

            try {

                $kyc->update([
                    'status' => $request->status ?? $kyc->status, 
                    'memo' => $request->memo,
                ]);

                if ($request->status && $request->status == 'approved') {
                    $user = UserProfile::where('user_id', $request->user_id)->first();
                    $user->update(['is_kyc_verified' => 'y']);
                }

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => '수정되었습니다.',
                    'url' => route('admin.user.kyc.view', ['id' => $kyc->id]),
                ]);

            } catch (\Exception $e) {
                DB::rollBack();

                \Log::error('Failed to update kycVerification by admin', ['error' => $e->getMessage()]);

                return response()->json([
                    'status' => 'error',
                    'message' => '예기치 못한 오류가 발생했습니다.',
                ]);
            }
        }
    }

    public function export(Request $request)
    {
        $current = now()->toDateString();

        return Excel::download(new KycExport($request->all()), 'KYC 인증 목록 '.$current.'.xlsx');
    }

}
    