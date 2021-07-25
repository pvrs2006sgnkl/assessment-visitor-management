<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Units;

class Unit extends Component
{
    public $units, $unit_no, $unit_type, $contact_number, $unit_id;
    public $isModalOpen = 0;

    public function render()
    {
        $this->units = Units::whereNull('deleted_at')
            ->orderBy('block_no')
            ->orderBy('unit_no')
            ->get();
        return view('livewire.unit.list');
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
        $this->unit_no = '';
        $this->unit_type = '';
        $this->contact_number = '';
    }
    
    public function store()
    {
        $this->validate([
            'unit_no' => 'required',
            'unit_type' => 'required',
            'contact_number' => 'required|integer',
        ]);

        Units::updateOrCreate(['id' => $this->unit_id], [
            'unit_no' => $this->unit_no,
            'type' => $this->unit_type,
            'contact_number' => $this->contact_number,
        ]);

        session()->flash('message', $this->unit_id ? 'Unit successfully updated.' : 'Unit successfully created.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $detail = Units::findOrFail($id);
        $this->unit_id = $id;
        $this->unit_no = $detail->unit_no;
        $this->unit_type = $detail->type;
        $this->contact_number = $detail->contact_number;

        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Units::find($id)->delete();
        session()->flash('message', 'Unit has been deleted.');
    }

    public function redirect($id)
    {
        session()->flash('message', 'Unit has been preparing to redirect.');
    } 
}
