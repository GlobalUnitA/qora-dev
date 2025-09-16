<?php

namespace App\Http\Controllers\Profile;

use App\Models\Mining;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $data = $this->getDashboardData();

        return view('profile.dashboard', compact('data'));

    }

    private function getDashboardData()
    {

        $user = auth()->user()->profile;
        $grade = $user->grade->name;

        $childrens = $user->getChildrenTree(20);

        $all_count = collect($childrens)->flatten(1)->count();
        $direct_count = isset($childrens[1]) ? $childrens[1]->count() : 0;
        $group_sales = $user->getGroupSales();

        $mining = Mining::where('user_id', auth()->id())->get();
        $total_node_amount = $mining->sum('node_amount');
        $total_staking = $mining->sum('refund_coin_amount');

        return [
            'grade' => $grade,
            'all_count' => $all_count,
            'direct_count' => $direct_count,
            'group_sales' => $group_sales,
            'total_node_amount' => $total_node_amount,
            'total_staking' => $total_staking,
        ];
    }
}
