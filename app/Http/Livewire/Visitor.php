<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;

use Livewire\Component;
use App\Models\User;
use App\Models\Unit;
use App\Models\History;
use App\Models\Unituser;

use App\Rules\CovidRule;

use App\Helper\Utilites;

use Carbon\Carbon;

use DB;

class Visitor extends Component
{
    public $history_id, $user_id, $name, $email, $mobile_number, $nric, $user_type, $unit_id, $block_no, $unit_no;
    public $checkin_at, $checkout_at, $meet_person_name, $is_update;
    public $search_by_nric, $search_by_mobile;

    public $isModalOpen = 0;

    public function __construct()
    {
        $this->utilities = app(Utilites::class);
    }

    public function render(Request  $request)
    {
        $this->unit_id = (trim($this->unit_id) != '')? $this->unit_id : request()->unit;
        $this->user_type = config('general.user_types.VISITOR_USER');
        $this->by_nric = $this->search_by_nric;
        $this->by_mobile = $this->search_by_mobile;

        $this->list = $this->utilities->findAllUsersByUnitId($this);
        $this->units = $this->utilities->findAllUnits();
        return view('livewire.visitor.list', ["list" => $this->list, "units" => $this->units]);
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
        $this->user_type = config('general.user_types.VISITOR_USER');
        $this->checkin_at = now()->format('d-m-Y H:m');
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
                new CovidRule($this->user_type, config('general.rules.MAX_ALLOWED_VISISTOR_COUNT'))
            ],
            'user_id' => 'nullable|integer',
            'checkin_at' => 'required|date',
            'checkout_at' => 'nullable|date|after:checkin_at',
        ]);

        DB::transaction(function () {
            if(!empty($this->user_id)) {
                $user = User::find($this->user_id);
            }
            else {
                $user = new User();
            }

            $unit_info = Unit::find($this->unit_id);

            $user->name = $this->name;
            $user->email = $this->email;
            $user->mobile_number = $this->mobile_number;
            $user->user_type = $this->user_type;
            $user->nric = $this->nric;
            $user->save();

            $user->unit()->sync($unit_info);

            // Prepare the visitor history params...
            $history = History::findOrNew($this->history_id);
            $history->user_id = $user->id;
            $history->unit_id = $this->unit_id;
            $history->meet_person_name = $this->meet_person_name;
            $history->entered_at = now()->parse($this->checkin_at);
            $history->exited_at = (!empty($this->checkout_at))? $this->checkout_at : null;
            $history->expired_at = now()->addDay();
            $history->save();

            session()->flash('message', $this->unit_id ? 'Visitor successfully updated.' : 'Visitor successfully created.');
        });

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($history_id, $unit_id, $user_id)
    {
        $this->history_id = $history_id;
        $this->unit_id = $unit_id;
        $this->user_id = $user_id;

        $detail = $this->utilities->findActiveVisitorDetail($this);

        $this->is_update = true;

        $this->history_id = $detail->id;
        $this->user_id = $detail->user_id;
        $this->block_no = $detail->unit->block_no;
        $this->unit_id = $detail->unit_id;
        $this->name = $detail->user->name;
        $this->mobile_number = $detail->user->mobile_number;
        $this->nric = $detail->user->nric;
        $this->user_type = $detail->user->user_type;
        $this->checkin_at = (!empty($detail->entered_at))? $detail->entered_at : now()->format('dd-mm-YY hh:mm:i');
        $this->checkout_at = $detail->exited_at;
        $this->meet_person_name = $detail->meet_person_name;
        
        $this->openModalPopover();
    }
    
    public function delete($unit_id, $id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Visitor has been deleted.');
    }
}
