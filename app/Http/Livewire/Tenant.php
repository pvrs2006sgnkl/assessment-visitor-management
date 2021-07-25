<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;

use Livewire\Component;
use App\Models\User;
use App\Models\Units;

class Tenant extends Component
{
    public $name, $email, $mobile_number, $nric, $user_type, $unit_id, $unit_no, $user_id;
    public $isModalOpen = 0;

    public function render()
    {
        $this->unit_id = (trim($this->unit_id) != '')? $this->unit_id : request()->unit;
        $this->user_type = (trim($this->user_type) != '')? $this->user_type : 'tenant';

        $this->list = $this->findAllByUnitId($this->unit_id);
        $this->units = $this->findAllUnits();
        return view('livewire.tenant.list', ["list" => $this->list, "units" => $this->units]);
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
        $result = User::join('units', 'users.unit_id', 'units.id')
            ->whereNull('units.deleted_at')
            ->whereNull('users.deleted_at')
            ->where('users.user_type', 'tenant')
            ->orderBy('users.updated_at', 'desc')
            ->select('users.id as user_id',
                'users.name as name', 
                'users.email as email', 
                'users.mobile_number as mobile_number', 
                'users.user_type as user_type', 
                'users.nric as nric',
                'users.unit_id as unit_id',
                'units.block_no as block_no',
                'units.unit_no as unit_no',
                'units.type as unit_type',
                'units.contact_number as tenant_contact_number'
            );

        if(!empty($unit_id)) {
            $result->where('units.id', $unit_id);
        }
        return $result->get();
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
    }

    private function resetCreateForm(){
        $this->name = '';
        $this->email = '';
        $this->mobile_number = '';
        $this->user_type = 'tenant';
        $this->nric = '';
        // $this->unit_id = '';
        $this->unit_no = '';
        $this->user_id = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|integer',
            'user_type' => 'required',
            'nric' => 'required|string',
            'unit_id' => 'required|integer',
            'user_id' => 'integer',
        ]);

        User::updateOrCreate(['id' => $this->user_id], [
            'name' => $this->name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'user_type' => $this->user_type,
            'nric' => $this->nric,
            'unit_id' => $this->unit_id,
        ]);

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
        $this->unit_id = $detail->unit_id;
        $this->mobile_number = $detail->mobile_number;
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Unit has been deleted.');
    }
}
