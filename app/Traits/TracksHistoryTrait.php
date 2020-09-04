<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use App\History;

trait TracksHistoryTrait
{
    protected function track(Model $model, callable $func = null, $table = null, $id = null)
    {
        // Allow for overriding of table if it's not the model table
        $table = $table ?: $model->getTable();
        // Allow for overriding of id if it's not the model id
        $id = $model->id ?: $model->INDEXNO;
        $actor_id = Auth::id() ?: $model->id;
        // Allow for customization of the history record if needed
        $func = $func ?: [$this, 'getHistoryBody'];

        // Get the dirty fields and run them through the custom function, then insert them into the history table
        $this->getUpdated($model)
            ->map(function ($value, $field) use ($func, $model) {
                return call_user_func_array($func, [$model, $value, $field]);
            })
            ->each(function ($fields) use ($table, $id, $actor_id) {
                History::create([
                    'reference_table' => $table,
                    'reference_id'    => $id,
                    'actor_id'        => $actor_id,
                ] + $fields);
            });
    }

    protected function getHistoryBody($model, $value, $field)
    {
        // Get original attribute
        $origAttribute = $model->getOriginal($field);

        if ($field == 'email') {
            Mail::raw("Email field changed for user id # " . $model->id . ". Check histories table for more details.", function ($message) use ($model) {
                $message->from('clm_language@unog.ch', 'CLM Online [Do Not Reply]');
                $message->to(['allyson.frias@un.org', 'jeanpierre.gaviano@un.org'])->subject('Email field changed for user id # ' . $model->id);
            });
        }

        return [
            'body' => "Updated {$field} from {$origAttribute} to ${value}",
        ];
    }

    protected function getUpdated($model)
    {
        return collect($model->getDirty())->filter(function ($value, $key) {
            // We don't care if timestamps are dirty, we're not tracking those
            return !in_array($key, ['created_at', 'updated_at', 'UPDATED', 'remember_token', 'update_token', 'last_login_at', 'last_login_ip']);
        })->mapWithKeys(function ($value, $key) {
            // Take the field names and convert them into human readable strings for the description of the action
            // e.g. first_name -> first name
            return [str_replace('_', ' ', $key) => $value];
        });
    }
}
