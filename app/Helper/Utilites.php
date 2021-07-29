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
        $result = User::where('user_type', $request->user_type)
            ->whereHas('units', function($query) use($request){
                if(!empty($request->unit_id)) {
                    $query->where('unit_id', $request->unit_id);
                }

                if(!empty($request->by_nric)) {
                    $query->where('nric', 'LIKE', $request->by_nric."%");
                }

                if(!empty($request->by_mobile)) {
                    $query->where('mobile_number', 'LIKE', "%".$request->by_mobile."%");
                }
            });
        return $result->get();
    }

    public function getActiveVisitors($request)
    {
        $result = History::whereHas('users', function($query) use($request){
                        if(!empty($request['id'])) {
                            $query->where('unit_id', $request['id']);
                        }
                    })
                    ->whereNull('exited_at')
                    ->where('expired_at', '>=', now())
                    ->whereNull('deleted_at');

        return $result->get();
    }
}