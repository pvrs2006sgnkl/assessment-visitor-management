<div class="fixed z-5 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>?
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <input type="hidden" wire:model="user_id" />
                    <div class="">
                        <div class="mb-4">
                            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                                id="unit_no" wire:model="unit_id"
                                @if ($is_update) disabled @endif>
                                <option value="" >Select Unit</option>
                                @foreach($units as $i=>$record)
                                    <option value="{{$record->id}}" {{ $record->id == $unit_id ? 'selected="selected"' : '' }}>Blk {{ $record->block_no}} ( #{{ $record->unit_no}} )</option>
                                @endforeach
                            </select>
                            <input type="hidden" wire:model="unit_id" />
                            @error('unit_id') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Vistor Name" wire:model="name"
                                maxlength="100"
                                @if ($is_update) readonly @endif
                                >
                            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Whom you want to meet" wire:model="meet_person_name"
                                maxlength="100"
                                >
                            @error('meet_person_name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Enter Mobile Number" wire:model="mobile_number"
                                maxlength="10"
                                @if ($is_update) readonly @endif
                                >
                            @error('mobile_number') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Last 5 digit NRIC" wire:model="nric"
                                maxlength="5"
                                @if ($is_update) readonly @endif
                                >
                            @error('nric') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Entry time (DD-MM-YYYY HH:MM)" wire:model="checkin_at"
                                maxlength="30"
                                >
                            @error('checkin_at') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Exit time (DD-MM-YYYY HH:MM)" wire:model="checkout_at"
                                maxlength="30">
                            @error('checkout_at') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                </div>
                @error('unit_id') <span class="text-red-500">{{ $message }}</span>@enderror
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-bold text-gray-700 shadow-sm hover:text-gray-700 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Save
                        </button>
                    </span> &nbsp;&nbsp;
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button wire:click="closeModalPopover()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-bold text-gray-700 shadow-sm hover:text-gray-700 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Close
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
div.fixed.z-10.inset-0{
    margin: 5% 0 0 25%;
    overflow:none;
}

input[readonly],
select[readonly],
textarea[readonly],
input[disabled],
select[disabled],
textarea[disabled] {
    background-color:#f2f0f0;
}
</style>

@section('scripts')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<!--Load Script and Stylesheet -->
<script type="text/javascript" src="https://www.jqueryscript.net/demo/jQuery-Date-Time-Picke-Plugin-Simple-Datetimepicker/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="https://www.jqueryscript.net/demo/jQuery-Date-Time-Picke-Plugin-Simple-Datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet" />

<script>
        $(document).ready(function () {
			
			//DatePicker Example
			$('#datetimepicker').datetimepicker();
			
			//TimePicke Example
			$('#datetimepicker1').datetimepicker({
				datepicker:false,
				format:'H:i'
			});
			
			//Inline DateTimePicker Example
			$('#datetimepicker2').datetimepicker({
				format:'Y-m-d H:i',
				inline:true
			});
			
			//minDate and maxDate Example
			$('#datetimepicker3').datetimepicker({
				 format:'Y-m-d',
				 timepicker:false,
				 minDate:'-1970/01/02', //yesterday is minimum date
				 maxDate:'+1970/01/02' //tomorrow is maximum date
			});
			
			//allowTimes options TimePicker Example
			$('#datetimepicker4').datetimepicker({
				datepicker:false,
				allowTimes:[
				  '11:00', '13:00', '15:00', 
				  '16:00', '18:00', '19:00', '20:00'
				]
			});
			
		});
</script>
@endsection