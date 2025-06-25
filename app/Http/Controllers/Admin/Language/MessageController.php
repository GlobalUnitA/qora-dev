<?php

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use App\Models\MessageKey;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function __construct()
    {
        
    }
   
    public function index()
    {
        $data = MessageKey::all();
      
        return view('admin.language.message', compact('data'));
    }

   
}
