<x-layouts.app>
    <x-slot name="title">Import Attendance</x-slot>

    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Attendance</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Import Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">Import Attendance Records</h2>

                    <form method="POST" action="{{ route('attendance.import.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <x-form.select name="year" label="Year" :required="true">
                                <option value="">Select Year</option>
                                @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" @if(old('year', now()->year) == $y) selected @endif>{{ $y }}</option>
                                @endfor
                            </x-form.select>

                            <x-form.select name="month" label="Month" :required="true">
                                <option value="">Select Month</option>
                                @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" @if(old('month', now()->month) == $m) selected @endif>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                                @endfor
                            </x-form.select>
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload File *</label>
                            <div class="mt-1 flex justify-center px-6 py-12 border-2 border-dashed rounded-xl
                                        @error('file') border-red-300 @else border-gray-300 hover:border-indigo-400 @enderror transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 20M8 24l3.172-3.172a4 4 0 015.656 0L20 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500">
                                            <span>Click to upload</span>
                                            <input type="file" id="file" name="file" class="sr-only" required accept=".csv,.xlsx,.xls">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">CSV or Excel file (XLS, XLSX)</p>
                                </div>
                            </div>
                            @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('attendance.index') }}"
                               class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                                Import Records
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Template Info -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">File Template</h3>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <p class="text-sm text-blue-800 mb-4">
                            Download the template to ensure your file has the correct format and column structure.
                        </p>
                        <a href="{{ route('attendance.template') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow-md transition-all duration-200 w-full justify-center text-sm">
                            Download Template
                        </a>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Required Columns</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <svg class="mt-0.5 mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Staff Record Card Number</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="mt-0.5 mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Days Present</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="mt-0.5 mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Days Absent</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="mt-0.5 mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Days Late</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="mt-0.5 mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Days Leave</span>
                                </li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-medium text-gray-900 mb-2">File Format</h4>
                            <p class="text-sm text-gray-600 mb-2">
                                Supported formats: CSV, XLS, XLSX
                            </p>
                            <p class="text-sm text-gray-600">
                                First row should contain column headers.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
