@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')
@section('page-description', 'Update student information')

@section('content')
    <form class="max-w-7xl mx-auto" action="{{ route('dashboard.students.update', $student->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. Identity Information -->
        <div class="bg-white rounded-xl shadow-md border-gray-200 border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Identity Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-ui.text-input name="name" label="Student Name (English)" :value="$student->user->name" required max="255" />
                <x-ui.text-input name="name_bn" label="Student Name (Bangla)" :value="$student->name_bn" placeholder="বাংলায় নাম" max="255" />
                
                <!-- Registration No Display -->
                 <!-- <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID No.</label>
                    <input type="text" disabled value="{{ $student->registration_no ?? 'Not Generated' }}" 
                        class="min-h-12 px-4 w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 font-bold shadow-sm">
                </div> -->

                <x-ui.text-input name="email" label="Email Address" :value="$student->user->email" disabled />
                <x-ui.text-input name="phone" label="Mobile No." :value="$student->phone" max="20" />
                <x-ui.date-picker name="dob" label="Date of Birth" :value="$student->dob" />

                <x-ui.select name="gender" label="Gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </x-ui.select>

                <x-ui.select name="blood_group" label="Blood Group">
                    <option value="">Select Group</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ $student->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </x-ui.select>

                <x-ui.select name="religion" label="Religion">
                    <option value="">Select Religion</option>
                    @foreach(['Islam', 'Hinduism', 'Buddhism', 'Christianity', 'Other'] as $rel)
                         <option value="{{ $rel }}" {{ $student->religion == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                    @endforeach
                </x-ui.select>

                <div class="md:col-span-3">
                    <x-ui.image-input name="profile_image" label="Student Photo" :value="$student->profile_image"
                        helperText="Passport size photo recommended." />
                </div>
            </div>
        </div>

        <!-- 2. Family Information -->
        <div class="bg-white rounded-xl shadow-md border-gray-200 border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Family Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Father -->
                <x-ui.text-input name="father_name" label="Father's Name" :value="$student->father_name" max="255" />
                <x-ui.text-input name="father_occupation" label="Father's Occupation" :value="$student->father_occupation" max="255" />
                <x-ui.text-input name="father_phone" label="Father's Mobile No." :value="$student->father_phone" max="20" />

                <!-- Mother -->
                <x-ui.text-input name="mother_name" label="Mother's Name" :value="$student->mother_name" max="255" />
                <x-ui.text-input name="mother_occupation" label="Mother's Occupation" :value="$student->mother_occupation" max="255" />
                <x-ui.text-input name="mother_phone" label="Mother's Mobile No." :value="$student->mother_phone" max="20" />

                <!-- Guardian -->
                <x-ui.text-input name="guardian_name" label="Guardian's Name" :value="$student->guardian_name" max="255" />
                <x-ui.text-input name="guardian_phone" label="Guardian's Mobile No." :value="$student->guardian_phone" max="20" />
                <div class="hidden md:block"></div>
            </div>
        </div>

        <!-- 3. Address Information -->
        <div class="bg-white rounded-xl shadow-md border-gray-200 border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Address Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Present Address -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-700 border-b pb-2">Present Address</h4>
                    <x-ui.text-input name="present_holding" label="Holding No." :value="$student->present_holding" />
                    <x-ui.text-input name="present_village" label="Mohila/Vill" :value="$student->present_village" />
                    <x-ui.text-input name="present_po" label="Post Office (PO)" :value="$student->present_po" />
                    <x-ui.text-input name="present_ps" label="Police Station (PS)" :value="$student->present_ps" />
                    <x-ui.text-input name="present_dist" label="District" :value="$student->present_dist" />
                </div>

                <!-- Permanent Address -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-700 border-b pb-2">Permanent Address</h4>
                    <x-ui.text-input name="permanent_holding" label="Holding No." :value="$student->permanent_holding" />
                    <x-ui.text-input name="permanent_village" label="Mohila/Vill" :value="$student->permanent_village" />
                    <x-ui.text-input name="permanent_po" label="Post Office (PO)" :value="$student->permanent_po" />
                    <x-ui.text-input name="permanent_ps" label="Police Station (PS)" :value="$student->permanent_ps" />
                    <x-ui.text-input name="permanent_dist" label="District" :value="$student->permanent_dist" />
                </div>
            </div>
        </div>

        <!-- 4. Academic Qualification -->
        <div class="bg-white rounded-xl shadow-md border-gray-200 border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Academic Qualification</h3>
            </div>
            <div class="p-6 space-y-6">
                 <!-- SSC -->
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">SSC / Equivalent</h4>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <x-ui.text-input name="ssc_institute" label="Name of Institute" class="md:col-span-1" :value="$student->ssc_institute"/>
                        <x-ui.text-input name="ssc_board" label="Board" :value="$student->ssc_board"/>
                        <x-ui.text-input name="ssc_year" label="Passing Year" :value="$student->ssc_year"/>
                        <x-ui.text-input name="ssc_gpa" label="GPA" :value="$student->ssc_gpa"/>
                        <x-ui.text-input name="ssc_group" label="Group" :value="$student->ssc_group"/>
                    </div>
                </div>

                <!-- HSC -->
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">HSC / Equivalent</h4>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <x-ui.text-input name="hsc_institute" label="Name of Institute" class="md:col-span-1" :value="$student->hsc_institute"/>
                        <x-ui.text-input name="hsc_board" label="Board" :value="$student->hsc_board"/>
                        <x-ui.text-input name="hsc_year" label="Passing Year" :value="$student->hsc_year"/>
                        <x-ui.text-input name="hsc_gpa" label="GPA" :value="$student->hsc_gpa"/>
                        <x-ui.text-input name="hsc_group" label="Group" :value="$student->hsc_group"/>
                    </div>
                </div>

                <!-- Undergraduate -->
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Under Graduate / Equivalent</h4>
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <x-ui.text-input name="undergrad_institute" label="Name of Institute" class="md:col-span-1" :value="$student->undergrad_institute"/>
                        <x-ui.text-input name="undergrad_board" label="Board/University" :value="$student->undergrad_board"/>
                        <x-ui.text-input name="undergrad_year" label="Passing Year" :value="$student->undergrad_year"/>
                        <x-ui.text-input name="undergrad_gpa" label="CGPA/Class" :value="$student->undergrad_gpa"/>
                        <x-ui.text-input name="undergrad_group" label="Group" :value="$student->undergrad_group"/>
                        <x-ui.text-input name="undergrad_department" label="Department" :value="$student->undergrad_department"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Academic Information -->
        <div class="bg-white rounded-xl shadow-md border-gray-200 border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Academic Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class -->
                <x-ui.select name="class" label="Select Class" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ $student->class == $c ? 'selected' : '' }}>Class {{ $c }}</option>
                    @endforeach
                </x-ui.select>

                <!-- Course -->
                <x-ui.select name="course_id" label="Select Course">
                    <option value="">Select Course</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ ($student->batch->course_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </x-ui.select>

                <!-- Batch -->
                <x-ui.select name="batch_id" label="Select Batch" required>
                    <option value="">Select Batch</option>
                    @foreach($batches as $b)
                        @if(($student->batch->course_id ?? null) == $b->course_id)
                        <option value="{{ $b->id }}" {{ $student->batch_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endif
                    @endforeach
                </x-ui.select>

                <!-- Class Day -->
                <x-ui.select name="class_days" label="Class Days">
                    <option value="">Select Days</option>
                    <!-- Populated by JS -->
                </x-ui.select>

                <!-- Class Time -->
                <x-ui.select name="class_time" label="Class Time">
                    <option value="">Select Time</option>
                     <!-- Populated by JS -->
                </x-ui.select>
            </div>
        </div>

        <!-- 6. Payment Information -->
        <div class="bg-white rounded-xl shadow-md border-gray-200 border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-ui.text-input name="total_amount" label="Total Amount" type="number" :value="$student->total_amount" placeholder="0.00" />
                <x-ui.text-input name="paid_amount" label="Paid Amount" type="number" :value="$student->paid_amount" placeholder="0.00" />
                <x-ui.text-input name="due_amount" label="Due Amount" type="number" :value="$student->due_amount" placeholder="0.00" readonly class="bg-gray-50" />
                
                <x-ui.select name="payment_method" label="Payment Method">
                    <option value="">Select Method</option>
                    @foreach(['Cash', 'Bkash', 'Nagad', 'Rocket', 'Bank Transfer'] as $method)
                         <option value="{{ $method }}" {{ $student->payment_method == $method ? 'selected' : '' }}>{{ $method }}</option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>

        <!-- Global Actions -->
        <div class="flex justify-end space-x-3 mb-10">
            <a href="{{ route('dashboard.students.index') }}"
                class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                Update Student
            </button>
        </div>

    </form>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
             // Elements
            const els = {
                class: document.getElementById('class'),
                course: document.getElementById('course_id'),
                batch: document.getElementById('batch_id'),
                day: document.getElementById('class_days'),
                time: document.getElementById('class_time'),
            };

            // Store info
            let currentBatches = [];

            // 1. Initial Load: Populate Day/Time if Batch Selected
            if(els.batch.value) {
                // Fetch batch info to get schedule or find from available data?
                // We don't have schedule in options dataset. 
                // We should fetch it or find it.
                // Or since we only need it for display day/time, we can fetch it via API or pass in dataset.
                // Assuming we can fetch it via API.
                fetchBatchSchedule(els.batch.value);
            }

            function fetchBatchSchedule(batchId) {
                 // Or we could have used getBatches to get schedule.
                 // But we have a specific batch ID.
                 // Let's reuse getBatches for the course logic which gives us schedules.
                 // But for initial load, we might just need to fetch the single batch?
                 // Or we can just re-fetch the batch details.
                 // Or, simpler, let's just trigger the 'Course Change' logic IF we want to populate local cache?
                 // But we don't want to reset selections.
                 
                 // Alternative: Just fetch the batches for the current course and fill cache.
                 if(els.course.value) {
                     fetch(`{{ route('dashboard.students.index') }}/get-batches/${els.course.value}`)
                        .then(res => res.json())
                        .then(data => {
                            currentBatches = data;
                            // Update day/time based on selected batch
                            const currentBatch = data.find(b => b.id == els.batch.value);
                            if(currentBatch) updateScheduleUI(currentBatch);
                        });
                 }
            }

            // 2. Class Change -> Fetch Courses
            els.class.addEventListener('change', function() {
                const classVal = this.value;
                resetSelect(els.course, 'Select Course');
                resetSelect(els.batch, 'Select Batch');
                resetSelect(els.day, 'Select Days');
                resetSelect(els.time, 'Select Time');

                if (classVal) {
                    fetch(`{{ route('dashboard.students.index') }}/get-courses/${classVal}`)
                        .then(res => res.json())
                        .then(data => {
                            populateSelect(els.course, data);
                        });
                }
            });

            // 3. Course Change -> Fetch Batches
            els.course.addEventListener('change', function() {
                const courseId = this.value;
                resetSelect(els.batch, 'Select Batch');
                resetSelect(els.day, 'Select Days');
                resetSelect(els.time, 'Select Time');

                if (courseId) {
                    fetch(`{{ route('dashboard.students.index') }}/get-batches/${courseId}`)
                        .then(res => res.json())
                        .then(data => {
                            currentBatches = data;
                            populateSelect(els.batch, data);
                        });
                }
            });

            // 4. Batch Change -> Populate Day/Time
            els.batch.addEventListener('change', function() {
                const batchId = this.value;
                resetSelect(els.day, 'Select Days');
                resetSelect(els.time, 'Select Time');
                
                if (batchId && currentBatches.length > 0) {
                    const batch = currentBatches.find(b => b.id == batchId);
                    if(batch) updateScheduleUI(batch);
                } else if (batchId) {
                    // Fallback if not in cache (e.g. initial load logic mismatch)
                     fetchBatchSchedule(batchId);
                }
            });

            function updateScheduleUI(batch) {
                 if (batch && batch.schedule) {
                    const parts = batch.schedule.split(':');
                    const day = parts[0] ? parts[0].trim() : '';
                    const time = parts[1] ? parts[1].trim() : '';
                    
                    if(day) {
                         const opt = new Option(day, day, true, true);
                         els.day.add(opt);
                         updateUI(els.day);
                    }
                    if(time) {
                         const opt = new Option(time, time, true, true);
                         els.time.add(opt);
                         updateUI(els.time);
                    }
                }
            }

             // Helpers
            function resetSelect(el, placeholder) {
                el.innerHTML = `<option value="">${placeholder}</option>`;
                updateUI(el);
            }

            function populateSelect(el, items) {
                items.forEach(item => {
                    el.add(new Option(item.name, item.id));
                });
                updateUI(el);
            }
            
            // Payment Calculation
            const totalInput = document.querySelector('input[name="total_amount"]');
            const paidInput = document.querySelector('input[name="paid_amount"]');
            const dueInput = document.querySelector('input[name="due_amount"]');

            function calculateDue() {
                const total = parseFloat(totalInput.value) || 0;
                const paid = parseFloat(paidInput.value) || 0;
                dueInput.value = (total - paid).toFixed(2);
            }

            totalInput.addEventListener('input', calculateDue);
            paidInput.addEventListener('input', calculateDue);

            // Helper for x-ui update (Reuse or duplicated logic? 
            // In create.blade.php I duplicated it. I should probably duplicate it here too or make it global.)
            // I'll duplicate 'updateUI' function here for safety as it wasn't global.
             function updateUI(nativeSelect) {
                const name = nativeSelect.id;
                const list = document.getElementById('select-list-' + name);
                const textSpan = document.getElementById('select-text-' + name);
                
                if(list && textSpan) {
                    list.innerHTML = '';
                    let hasSelection = false;
                    
                    Array.from(nativeSelect.options).forEach(option => {
                        if (option.value === "" && option.disabled) return;

                        const li = document.createElement('li');
                        li.className = 'px-4 py-2 hover:bg-emerald-50 cursor-pointer text-sm text-gray-700 hover:text-bd-green transition-colors';
                        li.textContent = option.textContent;
                        li.onclick = () => selectOption(name, option.value, option.textContent);
                        list.appendChild(li);

                        if (option.selected && option.value !== "") {
                            textSpan.textContent = option.textContent;
                            textSpan.classList.remove('text-gray-500');
                            textSpan.classList.add('text-gray-900');
                            hasSelection = true;
                        }
                    });

                    if (!hasSelection) {
                         textSpan.textContent = nativeSelect.options[0]?.textContent || 'Select...';
                         if(nativeSelect.value == "") {
                             textSpan.classList.remove('text-gray-900');
                             textSpan.classList.add('text-gray-500');
                         }
                    }
                }
            }
        });
    </script>
    @endpush
@endsection