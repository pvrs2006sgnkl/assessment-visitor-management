<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;

use Livewire\Component;
use App\Models\User;
use App\Models\Units;
use App\Models\History;

use App\Rules\CovidRule;

use Carbon\Carbon;

use DB;

class Visitor extends Component
{
    public $history_id, $user_id, $name, $email, $mobile_number, $nric, $user_type, $unit_id, $block_no, $unit_no;
    public $checkin_at, $checkout_at, $meet_person_name, $is_update;
    public $search_by_nric, $search_by_mobile;

    public $isModalOpen = 0;

    public function render(Request $request)
    {
        $this->unit_id = (trim($this->unit_id) != '')? $this->unit_id : $request->unit;
        $this->user_type = 'visitor';

        $this->list = $this->findAllByUnitId($this->unit_id);
        $this->units = $this->findAllUnits();
        return view('livewire.visitor.list', ["list" => $this->list, "units" => $this->units]);
    }

    private function findAllUnits()
    {
        return Units::whereNull('deleted_at')
            ->orderBy('block_no')
            ->orderBy('unit_no')
            ->get();
    }

    private function findAllByUnitId($unit_id)
    {
        $result = Units::join('histories', 'units.id', 'histories.unit_id')
            ->join('users', 'users.id', 'histories.user_id')
            ->whereNull('users.deleted_at')
            ->where('users.user_type', 'visitor')
            ->where('histories.expired_at', '>=', now()->startOfMonth()->subMonth(3)->endOfDay())
            ->orderBy('units.block_no', 'asc')
            ->orderBy('units.unit_no', 'asc')
            ->orderBy('histories.entered_at', 'desc')
            ->select('histories.id as history_id',
                'users.id as user_id',
                'users.name as name',
                'users.email as email', 
                'users.mobile_number as mobile_number', 
                'users.user_type as user_type', 
                'users.nric as nric',
                'units.block_no as block_no',
                'units.unit_no as unit_no',
                'units.type as unit_type',
                'histories.unit_id as unit_id',
                'histories.meet_person_name as meeting_person',
                'histories.entered_at as entered_at',
                'histories.exited_at as exited_at',
                'histories.meet_person_name as meeting_person',
            );

        if(!empty($unit_id)) {
            $result->where('histories.unit_id', $unit_id);
        }
        return $result->get();
    }

    private function findAllActiveVisitorsByUnitId($unit_id)
    {
        if(empty($unit_id)){
            return collect([]);
        }

        return Units::join('histories', 'units.id', 'histories.unit_id')
            ->join('users', 'users.id', 'histories.user_id')
            ->where('histories.unit_id', $unit_id)
            ->whereNull('users.deleted_at')
            ->whereNull('histories.exited_at')
            ->where('users.user_type', 'visitor')
            // ->whereBetween('histories.expired_at', [now()->startOfDay(), now()->endOfDay()])
            ->orderBy('users.updated_at', 'desc')
            ->select('histories.id as history_id',
                'users.id as user_id',
                'users.name as name',
                'users.email as email', 
                'users.mobile_number as mobile_number', 
                'users.user_type as user_type', 
                'users.nric as nric',
                'units.block as block_no',
                'units.unit_no as unit_no',
                'units.type as unit_type',
                'histories.unit_id as unit_id',
                'histories.meet_person_name as meeting_person',
                'histories.entered_at as entered_at',
                'histories.exited_at as exited_at',
                'histories.meet_person_name as meeting_person',
            )
            ->get();
    }

    private function findOneActiveVisitorsByUnitId($unit_id, $user_id, $history_id)
    {
        if(empty($unit_id)){
            return collect([]);
        }

        $record = Units::join('histories', 'units.id', 'histories.unit_id')
            ->join('users', 'users.id', 'histories.user_id')
            ->where('histories.id', $history_id)
            // ->where('histories.unit_id', $unit_id)
            ->whereNull('users.deleted_at')
            // ->whereNull('histories.exited_at')
            ->where('users.user_type', 'visitor')
            // ->whereBetween('histories.expired_at', [now()->startOfDay(), now()->endOfDay()])
            ->orderBy('users.updated_at', 'desc')
            ->select('histories.id as history_id',
                'users.id as user_id',
                'users.name as name',
                'users.email as email', 
                'users.mobile_number as mobile_number', 
                'users.user_type as user_type', 
                'users.nric as nric',
                'units.unit_no as unit_no',
                'units.type as unit_type',
                'histories.unit_id as unit_id',
                'histories.meet_person_name as meeting_person',
                'histories.entered_at as entered_at',
                'histories.exited_at as exited_at',
                'histories.meet_person_name as meeting_person',
            )->first();

            return $record;
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }

    public function search()
    {
        $this->resetCreateForm();
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm(){
        // $this->unit_id = '';
        $this->history_id = '';
        $this->user_id = '';
        $this->name = '';
        $this->mobile_number = '';
        $this->nric = '';
        $this->user_type = 'visitor';
        $this->checkin_at = '';
        $this->checkout_at = '';
        $this->meet_person_name = '';
        $this->is_update = false;
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'mobile_number' => 'required',
            'user_type' => 'required',
            'nric' => 'required|integer',
            'unit_id' => [
                'required',
                'integer',
                new CovidRule(5)
            ],
            'history_id' => 'nullable|integer',
            'checkin_at' => 'required|date',
            'checkout_at' => 'nullable|date|after:checkin_at',
        ]);

        DB::transaction(function () {

            if(empty($this->history_id)) {
                $user_obj = User::create([
                    'name' => $this->name,
                    'mobile_number' => $this->mobile_number,
                    'user_type' => $this->user_type,
                    'nric' => $this->nric,
                ]);
    
                $this->user_id = $user_obj->id;

                $this->checkin_at = Carbon::parse($this->checkin_at);

                $history = History::create([
                    'user_id' => $this->user_id,
                    'unit_id' => $this->unit_id,
                    'meet_person_name' => $this->meet_person_name,
                    'entered_at' => $this->checkin_at,
                    'expired_at' => now()->addDay(),
                ]);
            } else {

                $history = History::find($this->history_id);
                $history->unit_id = $this->unit_id;
                $history->meet_person_name = $this->meet_person_name;
                $history->entered_at = $this->checkin_at;
                $history->exited_at = $this->checkout_at;
                $history->save();
            }
            session()->flash('message', $this->unit_id ? 'Visitor successfully updated.' : 'Visitor successfully created.');
        });

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($unit_id, $user_id, $history_id)
    {
        $detail = $this->findOneActiveVisitorsByUnitId($unit_id, $user_id, $history_id);

        $this->is_update = true;

        $this->history_id = $detail->history_id;
        $this->user_id = $detail->user_id;
        $this->block_no = $detail->block_no;
        $this->unit_id = $detail->unit_id;
        $this->name = $detail->name;
        $this->mobile_number = $detail->mobile_number;
        $this->nric = $detail->nric;
        $this->user_type = $detail->user_type;
        $this->checkin_at = $detail->entered_at;
        $this->checkout_at = $detail->exited_at;
        $this->meet_person_name = $detail->meeting_person;
        $this->openModalPopover();
    }
    
    public function delete($unit_id, $id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Visitor has been deleted.');
    }
}
