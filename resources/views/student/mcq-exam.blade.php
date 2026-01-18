@extends('layouts.admin')

@section('title', 'Take Exam - ' . $exam->name)

@section('content')
<div class="min-h-screen bg-gray-900" id="exam-app">
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Header with Timer and Exam Info -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6 sticky top-0 z-50">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $exam->name }}</h1>
                    <p class="text-gray-400 text-sm mt-1">Multiple Choice Questions</p>
                    <p class="text-gray-500 text-xs mt-1">Total Marks: {{ $exam->total_marks }} • Duration: {{ $exam->duration }} minutes</p>
                </div>
                <div class="text-right">
                    <div id="timer" class="text-4xl font-mono font-bold text-green-400 mb-1">--:--</div>
                    <p class="text-gray-400 text-sm">Time Remaining</p>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-400 mb-2">
                    <span class="font-medium">Progress</span>
                    <span id="progress-text" class="font-medium">0/{{ $questions->count() }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div id="progress-bar" class="bg-gradient-to-r from-indigo-600 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Question Navigation Grid -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-white font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Question Navigator
            </h3>
            <div class="flex flex-wrap gap-2" id="question-nav">
                @foreach($questions as $index => $question)
                <button type="button"
                        onclick="goToQuestion({{ $index }})" 
                        id="nav-{{ $index }}"
                        data-question-index="{{ $index }}"
                        class="question-nav-btn w-12 h-12 rounded-lg text-sm font-semibold transition-all duration-200 transform hover:scale-105
                               {{ isset($savedAnswers[$question->id]) ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}"
                        title="Question {{ $index + 1 }}{{ isset($savedAnswers[$question->id]) ? ' (Answered)' : '' }}">
                    {{ $index + 1 }}
                </button>
                @endforeach
            </div>
            
            <!-- Legend -->
            <div class="flex items-center gap-6 mt-4 pt-4 border-t border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-green-600"></div>
                    <span class="text-gray-400 text-sm">Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gray-700"></div>
                    <span class="text-gray-400 text-sm">Not Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 ring-2 ring-indigo-400"></div>
                    <span class="text-gray-400 text-sm">Current</span>
                </div>
            </div>
        </div>

        <!-- Questions Form -->
        <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}">
            @csrf
            
            @foreach($questions as $index => $question)
            <div id="question-{{ $index }}" 
                 class="question-panel bg-gray-800 rounded-lg shadow-lg p-8 mb-6 transition-all duration-300 {{ $index > 0 ? 'hidden' : '' }}"
                 data-question-index="{{ $index }}">
                
                <!-- Question Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            Question {{ $index + 1 }} of {{ $questions->count() }}
                        </span>
                        <span class="text-gray-400 text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            {{ $question->marks ?? 1 }} {{ ($question->marks ?? 1) > 1 ? 'marks' : 'mark' }}
                        </span>
                    </div>
                    <div id="question-status-{{ $index }}" class="text-sm font-medium">
                        @if(isset($savedAnswers[$question->id]))
                        <span class="text-green-400 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Answered
                        </span>
                        @else
                        <span class="text-gray-500 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Not Answered
                        </span>
                        @endif
                    </div>
                </div>
                
                <!-- Question Text -->
                <div class="bg-gray-900 rounded-lg p-6 mb-6 border-l-4 border-indigo-600">
                    <p class="text-white text-lg leading-relaxed">{!! nl2br(e($question->question)) !!}</p>
                </div>
                
                <!-- MCQ Options -->
                @if($question->type === 'mcq')
                <div class="space-y-3">
                    @foreach(['A', 'B', 'C', 'D'] as $optKey)
                    @php 
                        $optionField = 'option_' . strtolower($optKey);
                        $optionValue = $question->{$optionField};
                    @endphp
                    @if($optionValue)
                    <label class="flex items-start p-5 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition-all duration-200 transform hover:scale-[1.02] option-label group" 
                           data-question="{{ $question->id }}"
                           data-option="{{ $optKey }}">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="{{ $optKey }}"
                               class="w-5 h-5 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-700 answer-input mt-0.5"
                               data-question-id="{{ $question->id }}"
                               data-question-index="{{ $index }}"
                               {{ ($savedAnswers[$question->id] ?? '') === $optKey ? 'checked' : '' }}>
                        <span class="ml-4 text-white flex-1">
                            <span class="font-bold text-indigo-400 mr-3 text-lg">{{ $optKey }}.</span>
                            <span class="text-base">{{ $optionValue }}</span>
                        </span>
                        <svg class="w-6 h-6 text-green-400 opacity-0 group-has-[:checked]:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </label>
                    @endif
                    @endforeach
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-700">
                    <button type="button" 
                            onclick="goToQuestion({{ $index - 1 }})" 
                            class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 font-medium flex items-center gap-2 {{ $index === 0 ? 'invisible' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </button>
                    
                    <div class="flex items-center gap-3">
                        @if($index === $questions->count() - 1)
                        <button type="button"
                                onclick="confirmSubmit()"
                                id="submit-btn" 
                                class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-bold shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Exam
                        </button>
                        @else
                        <button type="button" 
                                onclick="goToQuestion({{ $index + 1 }})" 
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 font-medium flex items-center gap-2">
                            Next
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="submit-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-lg shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Submit Exam?</h3>
            <p class="text-gray-400 mb-2">Are you sure you want to submit your exam?</p>
            <p id="unanswered-warning" class="text-yellow-400 text-sm mb-4"></p>
            <div class="flex gap-3 mt-6">
                <button type="button" 
                        onclick="closeSubmitModal()" 
                        class="flex-1 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" 
                        onclick="submitExam()" 
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-bold">
                    Yes, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Configuration
    let remainingTime = {{ $timeRemaining }};
    let currentQuestion = 0;
    const totalQuestions = {{ $questions->count() }};
    const attemptId = {{ $attempt->id }};
    const saveAnswerUrl = '{{ route("student.exams.save-answer", $attempt) }}';
    const csrfToken = '{{ csrf_token() }}';
    let answeredCount = {{ count($savedAnswers) }};
    let autoSaveTimeout = null;

    // Timer Management
    function updateTimer() {
        if (remainingTime <= 0) {
            autoSubmitExam();
            return;
        }
        
        remainingTime--;
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        const timerEl = document.getElementById('timer');
        timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        // Color coding based on time remaining
        if (remainingTime <= 60) {
            timerEl.classList.remove('text-green-400', 'text-yellow-400');
            timerEl.classList.add('text-red-400');
        } else if (remainingTime <= 300) {
            timerEl.classList.remove('text-green-400', 'text-red-400');
            timerEl.classList.add('text-yellow-400');
        }
    }
    
    setInterval(updateTimer, 1000);
    updateTimer();

    // Question Navigation
    function goToQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        // Hide all questions
        document.querySelectorAll('.question-panel').forEach((el, i) => {
            el.classList.toggle('hidden', i !== index);
        });
        
        // Update navigation button styles
        document.querySelectorAll('.question-nav-btn').forEach((btn, i) => {
            if (i === index) {
                btn.classList.add('ring-2', 'ring-indigo-400', 'bg-indigo-600');
            } else {
                btn.classList.remove('ring-2', 'ring-indigo-400', 'bg-indigo-600');
            }
        });
        
        currentQuestion = index;
        
        // Scroll to top smoothly
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Update Progress
    function updateProgress() {
        answeredCount = document.querySelectorAll('.answer-input:checked').length;
        document.getElementById('progress-text').textContent = `${answeredCount}/${totalQuestions}`;
        const percentage = (answeredCount / totalQuestions) * 100;
        document.getElementById('progress-bar').style.width = `${percentage}%`;
    }

    // Auto-save Answer
    function saveAnswer(questionId, answer, questionIndex) {
        // Update UI immediately
        const navBtn = document.getElementById(`nav-${questionIndex}`);
        navBtn.classList.remove('bg-gray-700', 'text-gray-300');
        navBtn.classList.add('bg-green-600', 'text-white', 'shadow-lg');
        
        // Update question status
        const statusEl = document.getElementById(`question-status-${questionIndex}`);
        if (statusEl) {
            statusEl.innerHTML = `
                <span class="text-green-400 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Answered
                </span>
            `;
        }
        
        updateProgress();
        
        // Debounced save to server
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
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
            })
            .then(res => res.json())
            .then(data => {
                if (data.remaining_time) {
                    remainingTime = data.remaining_time;
                }
            })
            .catch(err => console.error('Auto-save failed:', err));
        }, 500);
    }

    // Answer Input Event Listeners
    document.querySelectorAll('.answer-input').forEach(input => {
        input.addEventListener('change', function() {
            const questionId = this.dataset.questionId;
            const questionIndex = this.dataset.questionIndex;
            const answer = this.value;
            saveAnswer(questionId, answer, questionIndex);
        });
    });

    // Submit Confirmation
    function confirmSubmit() {
        const unanswered = totalQuestions - answeredCount;
        const warningEl = document.getElementById('unanswered-warning');
        
        if (unanswered > 0) {
            warningEl.textContent = `You have ${unanswered} unanswered question${unanswered > 1 ? 's' : ''}.`;
        } else {
            warningEl.textContent = 'All questions answered!';
            warningEl.classList.remove('text-yellow-400');
            warningEl.classList.add('text-green-400');
        }
        
        document.getElementById('submit-modal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submit-modal').classList.add('hidden');
    }

    function submitExam() {
        window.onbeforeunload = null;
        document.getElementById('exam-form').submit();
    }

    function autoSubmitExam() {
        window.onbeforeunload = null;
        alert('Time is up! Your exam will be submitted automatically.');
        document.getElementById('exam-form').submit();
    }

    // Prevent Accidental Navigation
    window.onbeforeunload = function(e) {
        e.preventDefault();
        return "Are you sure you want to leave? Your progress is saved but you may not be able to return.";
    };
    
    document.getElementById('exam-form').onsubmit = function() {
        window.onbeforeunload = null;
    };

    // Initialize
    updateProgress();
    goToQuestion(0);
</script>

<style>
    /* Custom styles for better UX */
    .option-label:has(input:checked) {
        @apply bg-indigo-700 border-2 border-indigo-500;
    }
    
    /* Smooth transitions */
    .question-panel {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Pulse animation for timer when critical */
    #timer.text-red-400 {
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
</style>
@endsection
