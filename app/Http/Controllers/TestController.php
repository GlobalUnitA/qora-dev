<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Coin;
use App\Models\Asset;
use App\Models\Income;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use PragmaRX\Google2FA\Google2FA;
use Carbon\Carbon;


class TestController extends Controller
{

    public function __construct()
    {

    }
    public function index()
    {
        echo "test!!!!";
    }

}
