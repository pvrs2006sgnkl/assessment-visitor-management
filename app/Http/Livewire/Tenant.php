<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;

use Livewire\Component;
use App\Models\User;
use App\Models\Unit;
use App\Models\Rule;

use App\Helper\Utilites;

use DB;

class Tenant extends Component
{
    public $name, $email, $mobile_number, $nric, $user_type, $unit_id, $unit_no, $user_id;
    public $isModalOpen = 0;

    public function __construct()
    {
        $this->utilities = app(Utilites::class);
    }

    public function render()
    {
        $this->unit_id = (trim($this->unit_id) != '')? $this->unit_id : request()->unit;
        $this->user_type = config('general.user_types.TENANT_USER');

        $this->list = $this->utilities->findAllUsersByUnitId($this);
        $this->units = $this->utilities->findAllUnits();
        return view('livewire.tenant.list', ["list" => $this->list, "units" => $this->units]);
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
        $this->resetCreateForm();
    }

    private function resetCreateForm(){
        $this->name = '';
        $this->email = '';
        $this->mobile_number = '';
        $this->user_type = config('general.user_types.TENANT_USER');
        $this->nric = '';
        $this->unit = '';
        $this->user_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|string',
            'user_type' => 'required',
            'nric' => 'required|string',
            'unit_id' => 'required|integer',
            'user_id' => 'integer',
        ]);

        // Included the transaction if incase either on of the db activity failed, the last transaction will be rollack, to prevent the direty data to be captured
        DB::transaction(function () {

            if(!empty($this->user_id))
                $user = User::find($this->user_id);
            else
                $user = new User();

            $unit_info = Unit::find($this->unit_id);

            $user->name = $this->name;
            $user->email = $this->email;
            $user->mobile_number = $this->mobile_number;
            $user->user_type = $this->user_type;
            $user->nric = $this->nric;
            $user->save();

            $user->unit()->sync($unit_info);
        });

        session()->flash('message', $this->unit_id ? 'Tenant successfully updated.' : 'Tenant successfully created.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($unit_id, $id)
    {
        $detail = User::findOrFail($id);
        $this->user_id = $detail->id;
        $this->name = $detail->name;
        $this->email = $detail->email;
        $this->nric = $detail->nric;
        $this->user_type = $detail->user_type;
        $this->unit_id = $detail->unit->first()->id;
        $this->mobile_number = $detail->mobile_number;
        $this->openModalPopover();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Unit has been deleted.');
    }
}
