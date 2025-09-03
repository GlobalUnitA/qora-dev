<?php

namespace App\Http\Controllers\Wallet;

use App\Models\Wallet;
use App\Models\WalletTransfer;
use App\Models\WalletPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WalletController extends Controller
{
    public function __construct()
    {
        
    }

   
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth()->id())
            ->whereHas('coin', function ($query) {
                $query->where('is_active', 'y');
            })
            ->get();
        

        $wallet_infos = $wallets->map(function ($wallet) {
            return $wallet->getWalletInfo();
        });


        $wallet_policy = WalletPolicy::find(1);

        return view('wallet.wallet', compact('wallet_infos', 'wallet_policy'));
    }
}