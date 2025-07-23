<?php

namespace App\Http\Controllers\Proc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class PopupController extends Controller
{
    public function hide(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['message' => 'Invalid popup ID.'], 400);
        }

        $cookie_name = 'popup_hidden_' . $id;
        $cookie = cookie($cookie_name, '1', 60 * 24);

        return response()->json(['message' => 'Popup hidden.'])->cookie($cookie);
    }
}