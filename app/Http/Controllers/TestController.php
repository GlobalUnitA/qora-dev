<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KakaoApi;
use App\Models\UserOtp;
use App\Models\Admin;
use App\Models\AdminOtp;
use App\Models\Asset;
use App\Models\AssetPolicy;
use App\Models\AssetTransfer;
use App\Models\Income;
use App\Models\IncomeTransfer;
use App\Models\StakingPolicy;
use App\Models\StakingProfit;
use App\Models\StakingRefund;
use App\Models\StakingReward;
use App\Models\Staking;
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
    protected $kakaoApi;

    public function __construct()
    {
        $this->kakaoApi = new KakaoApi();
    }
   public function index()
    {
        
     
    }
}       