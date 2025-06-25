<?php

namespace App\Http\Controllers\Admin\Proc;

use App\Models\DepositToast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositToastController extends Controller
{
    public function markAsRead($id)
    {
        
        $toast = DepositToast::findOrFail($id);
        $toast->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 0,
            'url' => route('admin.asset.view', ['id' => $toast->deposit_id]),
        ]);
    }
}