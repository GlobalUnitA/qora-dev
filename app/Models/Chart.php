<?php 

namespace App\Models;

use App\Models\User;
use App\Models\Asset;
use App\Models\AssetTransfer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Chart
{
    public $data = [];
    public $mode;
    public $is_admin = 0;

    public function getChartData($user_id)
    {

        $user = User::find($user_id);
        
        $this->data[] = $this->writeNodeData($user);
    
        $this->addChildrenData($user);

    }

    protected function addChildrenData($parent_user)
    {
       
        $children_profile = $parent_user->profile->children()->with('user')->get();
        $children = $children_profile->pluck('user')->filter();

        if ($children->isEmpty()) {
            return;
        }

        foreach ($children as $child) {

            $this->data[] = $this->writeNodeData($child, $parent_user->id);

            if($this->mode == 'aff'){
                $this->addChildrenData($child);    
            } 
        }
    }

    protected function writeNodeData($user, $parent = null)
    {
        if($this->is_admin){
            $info = "이름 : {$user->name} <br> <i>{$user->account}</i> <br>";
            
        } else {
            $info = "<i>{$user->account}</i> <br>";
        }

        $assets = Asset::where('user_id', $user->id)
            ->whereHas('coin', function ($query) {
                $query->where('is_active', 'y');
            })
            ->get();

        foreach ($assets as $asset) {
            $sales = 0;
            $sales += AssetTransfer::where('asset_id', $asset->id)
                ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
                ->where('status', 'completed')
                ->get()
                ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());
            
            $info .= "{$asset->coin->code}: " . $sales . " <br>";
        }

        $node = [
            'id' => strval($user->id),
            'parent' => strval($parent),
            'info' => $info,
        ];
        
        return $node;
    }

}