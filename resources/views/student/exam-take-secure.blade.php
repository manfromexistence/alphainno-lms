@extends('layouts.admin')

@section('title', 'Take Exam')

@section('content')
<div class="min-h-screen bg-gray-900" id="exam-app">
    <!-- Anti-Cheating Warning Modal -->
    <div id="cheating-warning" class="hidden fixed inset-0 bg-black bg-opacity-90 z-[100] flex items-center justify-center">
        <div class="bg-red-600 rounded-lg p-8 max-w-md text-center">
            <svg class="w-16 h-16 mx-auto text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2 class="text-2xl font-bold text-white mb-2">Warning!</h2>
            <p class="text-white mb-4">You switched tabs/windows. This has been recorded.</p>
            <p class="text-red-200 text-sm mb-4">Tab switches: <span id="switch-count" class="font-bold">0</span></p>
            <p class="text-white text-sm">Multiple violations will result in automatic submission and flagging for cheating.</p>
            <button onclick="closeWarning()" class="mt-4 px-6 py-2 bg-white text-red-600 rounded-lg font-semibold hover:bg-gray-100">
                I Understand
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Header with Timer -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-4 mb-6 sticky top-0 z-50">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $exam->title }}</h1>
                    <p class="text-gray-400 text-sm">{{ $exam->type === 'mcq' ? 'Multiple Choice Questions' : 'Exam' }}</p>
                </div>
                <div class="text-right">
                    <div id="timer" class="text-3xl font-mono font-bold text-green-400">--:--</div>
                    <p class="text-gray-400 text-sm">Time Remaining</p>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-400 mb-1">
                    <span>Progress</span>
                    <span id="progress-text">0/{{ $total_questions }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
            <!-- Cheating Warning -->
            <div id="cheating-indicator" class="hidden mt-2 p-2 bg-red-900 rounded text-red-200 text-sm">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Tab switches detected: <span id="tab-switch-count">0</span>
            </div>
        </div>

        <!-- Question Navigation -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-4 mb-6">
            <div class="flex flex-wrap gap-2" id="question-nav">
                @foreach($questions as $index => $question)
                <button onclick="goToQuestion({{ $index }})" 
                        id="nav-{{ $index }}"
                        class="w-10 h-10 rounded-lg text-sm font-medium transition-all
                               {{ isset($answers[$question->id]) ? 'bg-green-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                    {{ $index + 1 }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Questions -->
        <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}">
            @csrf
            
            @foreach($questions as $index => $question)
            <div id="question-{{ $index }}" class="question-panel bg-gray-800 rounded-lg shadow-lg p-6 mb-6 {{ $index > 0 ? 'hidden' : '' }}">
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm">Question {{ $index + 1 }}</span>
                    <span class="text-gray-400 text-sm">{{ $question->marks ?? 1 }} marks</span>
                </div>
                
                <p class="text-white text-lg mb-6">{{ $question->question_text }}</p>
                
                @if($question->type === 'mcq' && $question->options)
                <div class="space-y-3">
                    @foreach($question->options as $optIndex => $option)
                    <label class="flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition option-label" data-question="{{ $question->id }}">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="{{ $option }}"
                               class="w-4 h-4 text-indigo-600 answer-input"
                               data-question-id="{{ $question->id }}"
                               data-question-index="{{ $index }}"
                               {{ ($answers[$question->id] ?? '') === $option ? 'checked' : '' }}>
                        <span class="ml-3 text-white">
                            <span class="font-semibold mr-2">{{ chr(65 + $optIndex) }}.</span>
                            {{ $option }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    <button type="button" onclick="goToQuestion({{ $index - 1 }})" 
                            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 {{ $index === 0 ? 'invisible' : '' }}">
                        ← Previous
                    </button>
                    @if($index === $total_questions - 1)
                    <button type="submit" id="submit-btn" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                        Submit Exam
                    </button>
                    @else
                    <button type="button" onclick="goToQuestion({{ $index + 1 }})" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Next →
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </form>
    </div>
</div>

<script>
    let remainingTime = {{ $remaining_time }};
    let currentQuestion = 0;
    const totalQuestions = {{ $total_questions }};
    const attemptId = {{ $attempt->id }};
    const saveAnswerUrl = '{{ route("student.exams.save-answer", $attempt) }}';
    const recordTabSwitchUrl = '{{ route("student.exams.record-tab-switch", $attempt) }}';
    const csrfToken = '{{ csrf_token() }}';
    let answeredCount = {{ count($answers) }};
    let tabSwitches = {{ $attempt->tab_switches ?? 0 }};
    const maxTabSwitches = 3;

    // Anti-Cheating: Tab/Window Switch Detection
    let isExamFocused = true;
    
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // User switched tab/window
            isExamFocused = false;
            handleTabSwitch();
        } else {
            isExamFocused = true;
        }
    });

    window.addEventListener('blur', function() {
        if (!document.hidden) {
            // Window lost focus but tab is still visible
            handleTabSwitch();
        }
    });

    function handleTabSwitch() {
        tabSwitches++;
        
        // Record on server
        fetch(recordTabSwitchUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                tab_switches: tabSwitches
            })
        }).catch(console.error);

        // Update UI
        document.getElementById('tab-switch-count').textContent = tabSwitches;
        document.getElementById('switch-count').textContent = tabSwitches;
        document.getElementById('cheating-indicator').classList.remove('hidden');
        
        // Show warning
        document.getElementById('cheating-warning').classList.remove('hidden');
        
        // Auto-submit if exceeded limit
        if (tabSwitches >= maxTabSwitches) {
            alert('You have exceeded the maximum allowed tab switches. Your exam will be submitted automatically and flagged for review.');
            document.getElementById('exam-form').submit();
        }
    }

    function closeWarning() {
        document.getElementById('cheating-warning').classList.add('hidden');
    }

    // Prevent right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });

    // Prevent copy/paste
    document.addEventListener('copy', function(e) {
        e.preventDefault();
        return false;
    });

    document.addEventListener('paste', function(e) {
        e.preventDefault();
        return false;
    });

    // Prevent keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Prevent F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
        if (e.keyCode === 123 || 
            (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
            (e.ctrlKey && e.keyCode === 85)) {
            e.preventDefault();
            return false;
        }
    });

    // Timer
    function updateTimer() {
        if (remainingTime <= 0) {
            document.getElementById('exam-form').submit();
            return;
        }
        
        remainingTime--;
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        const timerEl = document.getElementById('timer');
        timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        if (remainingTime <= 60) {
            timerEl.classList.remove('text-green-400', 'text-yellow-400');
            timerEl.classList.add('text-red-400');
        } else if (remainingTime <= 300) {
            timerEl.classList.remove('text-green-400');
            timerEl.classList.add('text-yellow-400');
        }
    }
    
    setInterval(updateTimer, 1000);
    updateTimer();

    // Question Navigation
    function goToQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        document.querySelectorAll('.question-panel').forEach((el, i) => {
            el.classList.toggle('hidden', i !== index);
        });
        currentQuestion = index;
    }

    // Auto-save answers
    document.querySelectorAll('.answer-input').forEach(input => {
        input.addEventListener('change', function() {
            const questionId = this.dataset.questionId;
            const questionIndex = this.dataset.questionIndex;
            const answer = this.value;
            
            // Update nav button
            document.getElementById(`nav-${questionIndex}`).classList.remove('bg-gray-700');
            document.getElementById(`nav-${questionIndex}`).classList.add('bg-green-600');
            
            // Update progress
            answeredCount = document.querySelectorAll('.answer-input:checked').length;
            document.getElementById('progress-text').textContent = `${answeredCount}/${totalQuestions}`;
            document.getElementById('progress-bar').style.width = `${(answeredCount / totalQuestions) * 100}%`;
            
            // Save to server
            fetch(saveAnswerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer: answer
                })
            }).then(res => res.json()).then(data => {
                if (data.remaining_time) {
                    remainingTime = data.remaining_time;
                }
            }).catch(console.error);
        });
    });

    // Prevent accidental navigation
    window.onbeforeunload = function() {
        return "Are you sure you want to leave? Your exam progress will be saved but you may not be able to return.";
    };
    
    document.getElementById('exam-form').onsubmit = function() {
        window.onbeforeunload = null;
    };

    // Fullscreen mode (optional but recommended)
    function enterFullscreen() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    }

    // Request fullscreen on start (optional)
    // enterFullscreen();
</script>
@endsection
