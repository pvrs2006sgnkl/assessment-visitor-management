<?php

namespace App\Helper;

use DB;

use App\Models\User;
use App\Models\Unit;
use App\Models\History;

class Utilites 
{
    public function findAllUnits()
    {
        return Unit::whereNull('deleted_by')
            ->orderBy('block_no')
            ->orderBy('unit_no')
            ->get();
    }

    public function findAllUsersByUnitId($request)
    {
        $results = User::where('user_type', $request->user_type);

        $results->whereHas('units', function($query) use($request){
            if(!empty($request->unit_id)) {
                $query->where('unit_id', $request->unit_id);
            }

            if(!empty($request->by_nric)) {
                $query->where('nric', 'LIKE', $request->by_nric."%");
            }

            if(!empty($request->by_mobile)) {
                $query->where('mobile_number', 'LIKE', $request->by_mobile."%");
            }
        });

        // Check the history only for vistor's
        if($this->isVisitor($request->user_type)) {
            $results->whereHas('history', function($query) use($request){
                if(!empty($request->unit_id)) {
                    $query->where('histories.unit_id', $request->unit_id);
                }

                if(!empty($request->user_id)) {
                    $query->where('histories.user_id', $request->user_id);
                }
            });
        }
        return $results->get();
    }

    public function getActiveVisitors($request)
    {
        return History::whereHas('users', function($query) use($request){
                    if(!empty($request['id'])) {
                        $query->where('unit_id', $request['id']);
                    }
                })
                ->whereNull('exited_at')
                ->where('expired_at', '>=', now())
                ->whereNull('deleted_at')
                ->get();
    }

    public function findActiveVisitorDetail($request)
    {
        return History::find($request->history_id);
    }

    private function isVisitor($user_type)
    {
        return ($user_type === config('general.user_types.VISITOR_USER'))? true : false; 
    }
}