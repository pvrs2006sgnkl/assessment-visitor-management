<x-slot name="header">
    <h2 class="text-center">Visitor Management</h2>
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <table class="table-fixed">
                <tr>
                    <td class="px-4 py-2">
                        <select class="shadow border rounded w-full py-2 px-5 text-gray-700 leading-tight"
                            id="unit_no" wire:model="unit_id"
                            @if ($is_update) disabled @endif>
                            <option value="" >Select Unit</option>
                            @foreach($units as $i=>$record)
                                <option value="{{$record->id}}" {{ $record->id == $unit_id ? 'selected="selected"' : '' }}>Blk {{ $record->block_no}} ( #{{ $record->unit_no}} )</option>
                            @endforeach
                        </select>
                        <input type="hidden" wire:model="unit_id" />
                        @error('unit_id') <span class="text-red-500">{{ $message }}</span>@enderror
                    </td>
                    <td class="px-4 py-2">
                        <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-8 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="NRIC" wire:model="search_by_nric"
                                maxlength="5"
                                >
                    </td>
                    <td class="px-4 py-2">
                        <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Mobile Number" wire:model="search_by_mobile"
                                maxlength="10"
                                >
                    </td>
                    <td class="px-4 py-4"></td>

                    <td class="px-4 py-2 w-200">
                        <button wire:change="search()"
                            class="btn-rounded font-bold py-2 px-4 rounded">Create Visitor
                        </button>
                    </td>
                </tr>
            </table>
            @if($isModalOpen)
            @include('livewire.visitor.create')
            @endif
            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 w-20">No.</th>
                        <th class="px-4 py-2">Occupant Name</th>
                        <th class="px-4 py-2">Contact Number #</th>
                        <th class="px-4 py-2">Unit No</th>
                        <th class="px-4 py-2">NRIC</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $i=>$record)
                    <tr>
                        <td class="border px-4 py-2 text-center">{{ $i+1 }}</td>
                        <td class="border px-4 py-2 text-center">{{ $record->name }}</td>
                        <td class="border px-4 py-2 text-center">{{ $record->mobile_number}}</td>
                        <td class="border px-4 py-2 text-center">Blk {{ $record->block_no}} ( #{{ $record->unit_no}} )</td>
                        <td class="border px-4 py-2 text-center">*****{{ $record->nric}}</td>
                        <td class="border px-4 py-2 text-center">
                            <button wire:click="delete({{ $record->unit_id }}, {{$record->user_id}})"
                                class="btn-rounded font-bold py-2 px-4 rounded">Delete
                            </button>
                            <button wire:click="edit({{ $record->unit_id }}, {{$record->user_id}}, {{ $record->history_id }})"
                                class="btn-rounded font-bold py-2 px-4 rounded ">Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                        <td class="border px-4 py-2 text-center" colspan="6">No records found</td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    .btn-rounded {
        border:solid 2px #CACACA;
        border-radius:5px;
        margin: 10px 10px 10px 0;
        float:right;
    }
</style>