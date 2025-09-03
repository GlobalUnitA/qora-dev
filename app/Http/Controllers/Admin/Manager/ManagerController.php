<?php

namespace App\Http\Controllers\Admin\Manager;

use App\Exports\AdminExport;
use App\Models\Admin;
use App\Models\AdminOtp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller
{
 
    public function __construct()
    {
        
    }

   
    public function list()
    {
        $list = Admin::where('admin_level', '<', 4)->paginate(10);

        return view('admin.manager.list', compact('list'));
    }

    public function view($id)
    {
        $admin = auth()->guard('admin')->user();

        if ($admin->admin_level < 4 && $admin->id != $id) {
            return redirect()->route('admin');
        }

        $view = Admin::find($id);

        return view('admin.manager.view', compact('view'));
    }

    public function create()
    {
        return view('admin.manager.create');
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account' => 'required|string|max:255|unique:admins',
            'password' => 'required|string|min:8|max:16',
            'admin_level' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            $admin = Admin::create([
                'name' => $validated['name'],
                'account' => $validated['account'],
                'password' => Hash::make($validated['password']),
                'admin_level' => $validated['admin_level'],
            ]);

            AdminOtp::create(['admin_id' => $admin->id]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '관리자가 추가되었습니다.',
                'url' => route('admin.manager.list'),
            ]);

        } catch (Exception $e) {
          
            DB::rollBack();

            \Log::error('Failed to create admin', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
            
        }        
    }

    public function update(Request $request)
    {

        $admin = Admin::find($request->id);

        if ($admin) {

            DB::beginTransaction();

            try {

                $admin->update([
                    'name' => $request->name ?? $admin->name,
                    'password' => $request->password ? Hash::make($request->password) : $admin->password,
                    'admin_level' => $request->admin_level ?? $admin->admin_level,
                ]); 

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => '수정되었습니다.',
                    'url' => route('admin.manager.view', ['id' => $admin->id]),
                ]);

            } catch (\Exception $e) {
                DB::rollBack();

                \Log::error('Failed to update admin', ['error' => $e->getMessage()]);

                return response()->json([
                    'status' => 'error',
                    'message' => '예기치 못한 오류가 발생했습니다.',
                ]);
            }
        }
    }

    public function delete(Request $request)
    {
        
        DB::beginTransaction();

        try {

            $admin = Admin::findOrFail($request->id);
            $admin->otp()?->delete();
            $admin->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '관리자가 삭제되었습니다',
                'url' => route('admin.manager.list'),
            ]);

        } catch (Exception $e) {
          
            DB::rollBack();

            \Log::error('Failed to delete admin', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
            
        }   
    }
   
    public function export(Request $request)
    {
        return Excel::download(new AdminExport($request->all()), 'admins.xlsx');
    }
}
