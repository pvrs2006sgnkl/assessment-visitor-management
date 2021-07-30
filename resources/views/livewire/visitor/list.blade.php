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
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Mobile Number" wire:model="search_by_mobile"
                                maxlength="10"
                                wire:keydown.enter="search"
                                >
                    </td>
                     <td class="px-4 py-2">
                        <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-8 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="NRIC" wire:model="search_by_nric"
                                maxlength="5"
                                wire:keydown.enter="search"
                                >
                    </td>
                    <td class="px-4 py-4"></td>

                    <td class="px-4 py-2 w-200">
                        <button wire:click="create()"
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
                        <th class="px-4 py-2">Guest Name</th>
                        <th class="px-3 py-2">Unit No</th>
                        <th class="px-3 py-2">Nric</th>
                        <th class="px-3 py-2">Mobile#</th>
                        <th class="px-4 py-2">Check In</th>
                        <th class="px-4 py-2">Check Out</th>
                        <th class="px-4 py-2" nowrap>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $i=>$record)
                        @foreach($record->units as $unit)
                            @foreach($record->history as $history)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $i+1 }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $record->name }} <br /> (*****{{ $record->nric}})</td>
                                    <td class="border px-3 py-2 text-center">Blk {{ $unit->block_no}} ( #{{ $unit->unit_no}} )</td>
                                    <td class="border px-3 py-2 text-center">*****{{ $record->nric}}</td>
                                    <td class="border px-3 py-2 text-center">{{ $record->mobile_number}}</td>
                                    <td class="border px-4 py-2 text-center">{{ $history->entered_at }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @if(empty($history->exited_at))
                                            <span class="text-blue-100">Still In</span>
                                        @else
                                            {{ $history->exited_at }}
                                        @endif
                                    </td>
                                    <td class="border py-2 text-center">
                                        <button wire:click="delete({{ $history->id }}, {{ $unit->id }}, {{$record->id}})"
                                            class="btn-rounded font-bold py-2 px-4 rounded">Delete
                                        </button>
                                        <button wire:click="edit({{ $history->id }}, {{ $unit->id }}, {{$record->id}})"
                                            class="btn-rounded font-bold py-2 px-4 rounded "> &nbsp; Edit &nbsp;
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
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

    .text-blue-100
    {
        color:#008080;
        font-size:1.2rem;
    }
</style>