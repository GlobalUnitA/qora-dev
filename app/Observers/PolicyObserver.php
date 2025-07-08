<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\Coin;
use App\Models\PolicyModifyLog;

class PolicyObserver
{
    public function updated($model)
    {
        foreach ($model->getChanges() as $column => $new_value) {

            if (in_array($column, ['created_at', 'updated_at'])) {
                continue;
            }

            $old_value = $model->getOriginal($column);

            if (is_numeric($old_value) && is_numeric($new_value)) {
                if ((float)$old_value === (float)$new_value) {
                    continue;
                }
            } elseif ($old_value === $new_value) {
                continue;
            }

            $description = $model->getColumnComment($column);
            $table_name = $model->getTable();

            if ($table_name === 'staking_policies') {
                continue;
                //$coin_code = optional(Coin::find($model->coin_id))->code;
                //$table_name .= $coin_code ? "_{$coin_code}" : '';
            }

            PolicyModifyLog::create([
                'policy_type' => $table_name,
                'policy_id' => $model->id,
                'column_name' => $column,
                'column_description' => $description,
                'old_value' => $old_value,
                'new_value' => $new_value,
                'admin_id' => Auth::guard('admin')->id() ?? null,
            ]);
        }
    }
}