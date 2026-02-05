<x-layouts.app>
    <x-slot name="title">Add Staff</x-slot>

    <div class="mb-6">
        <a href="{{ route('staff.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Staff List</a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Add New Staff Member</h2>

            <form method="POST" action="{{ route('staff.store') }}">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-form.input name="name" label="Full Name" :value="old('name')" :required="true" />

                    <x-form.input name="record_card_number" label="Record Card Number" :value="old('record_card_number')" :required="true" />

                    <x-form.select name="department_id" label="Department">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @if(old('department_id') == $dept->id) selected @endif>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select name="designation_id" label="Designation">
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                        <option value="{{ $designation->id }}" @if(old('designation_id') == $designation->id) selected @endif>
                            {{ $designation->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.input name="email" label="Email" type="email" :value="old('email')" />

                    <x-form.input name="phone" label="Phone" :value="old('phone')" />

                    <x-form.date-input name="joined_date" label="Joined Date" :value="old('joined_date')" />

                    <x-form.select name="status" label="Status" :required="true">
                        <option value="active" @if(old('status', 'active') == 'active') selected @endif>Active</option>
                        <option value="inactive" @if(old('status') == 'inactive') selected @endif>Inactive</option>
                        <option value="terminated" @if(old('status') == 'terminated') selected @endif>Terminated</option>
                    </x-form.select>

                    <div class="sm:col-span-2">
                        <x-form.textarea name="notes" label="Notes" :value="old('notes')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('staff.index') }}"
                       class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        Create Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
