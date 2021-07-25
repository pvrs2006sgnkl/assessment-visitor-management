<x-slot name="header">
    <h2 class="text-center">Unit Management</h2>
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

            <button wire:click="create()"
                class=" btn-rounded font-bold py-2 px-4 rounded">Create Unit
            </button>
            @if($isModalOpen)
            @include('livewire.unit.create')
            @endif
            
            <div wire:loading.flex wire:target="create">
                Processing please wait...
            </div>

            <div wire:loading.flex wire:target="redirect">
                Processing please wait...
            </div>

            <table class="table w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-2 w-20">No.</th>
                        <th class="px-3 py-2 w-20" nowrap>Block</th>
                        <th class="px-3 py-2 w-20" nowrap>Unit</th>
                        <th class="px-2 py-2 w-20">Type</th>
                        <th class="px-2 py-2 w-20" nowrap>Contact Number</th>
                        <th class="px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $i=>$unit)
                    <tr>
                        <td class="border px-2 py-2 text-center">{{ $i+1 }}</td>
                        <td class=" border px-3 py-2 text-center">{{ $unit->block_no }}</td>
                        <td class=" border px-3 py-2 text-center">#{{ $unit->unit_no }}</td>
                        <td class="border px-2 py-2 text-center">{{ $unit->type}}</td>
                        <td class="border px-2 py- text-center" nowrap >{{ $unit->contact_number}}</td>
                        <td class="border px-3 py-2 text-center">
                            <a href="{{route('visitor.list', "unit=".$unit->id)}}" class="btn-rounded font-bold py-2 px-4 rounded">View Visitors</a>
                            <a href="{{route('tenant.list', "unit=".$unit->id)}}" class="btn-rounded font-bold py-2 px-4 rounded">View Tenant</a>
                            <button wire:click="delete({{ $unit->id }})"
                                class="btn-rounded font-bold py-2 px-4 rounded">Delete
                            </button>
                            <button wire:click="edit({{ $unit->id }})"
                                class="btn-rounded font-bold py-2 px-4 rounded ">Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                        <tr><td class="border px-4 py-2 text-center" colspan="6">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div wire:loading.inline>
                Processing please wait...
            </div>
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