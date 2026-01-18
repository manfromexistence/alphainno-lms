@extends('layouts.admin')

@section('title', 'Take Exam')

@section('content')
<div class="min-h-screen bg-gray-50" id="exam-app">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-4">
                <!-- Timer Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 sticky top-4">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-full mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div id="timer" class="text-4xl font-mono font-bold text-primary">--:--</div>
                        <p class="text-gray-600 text-sm mt-2">Time Remaining</p>
                    </div>
                    
                    <!-- Progress -->
                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Progress</span>
                            <span id="progress-text" class="text-gray-900 font-semibold">0/{{ $total_questions }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div id="progress-bar" class="bg-primary h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <div class="bg-green-50 rounded-lg p-2 text-center border border-green-200">
                                <div id="answered-count" class="text-lg font-bold text-green-600">0</div>
                                <div class="text-xs text-gray-600">Answered</div>
                            </div>
                            <div class="bg-amber-50 rounded-lg p-2 text-center border border-amber-200">
                                <div id="unanswered-count" class="text-lg font-bold text-amber-600">{{ $total_questions }}</div>
                                <div class="text-xs text-gray-600">Remaining</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question Navigation -->
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                    <h3 class="text-gray-900 font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Questions
                    </h3>
                    <div class="grid grid-cols-5 gap-2" id="question-nav">
                        @foreach($questions as $index => $question)
                        <button onclick="goToQuestion({{ $index }})" 
                                id="nav-{{ $index }}"
                                class="aspect-square rounded-lg text-sm font-bold transition-all
                                       {{ isset($answers[$question->id]) ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300' }}">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                        <div class="flex items-center text-xs text-gray-600">
                            <div class="w-4 h-4 rounded bg-green-500 mr-2"></div>
                            Answered
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <div class="w-4 h-4 rounded bg-gray-100 border border-gray-300 mr-2"></div>
                            Not Answered
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Header -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $exam->type === 'mcq' ? 'Multiple Choice Questions' : 'Exam' }} • {{ $total_questions }} Questions
                            </p>
                        </div>
                        <div class="hidden sm:block bg-primary/10 rounded-lg p-4 border border-primary/20">
                            <div class="text-2xl font-bold text-primary">{{ $exam->total_marks }}</div>
                            <div class="text-xs text-gray-600">Total Marks</div>
                        </div>
                    </div>
                </div>

                <!-- Questions Form -->
                <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}">
                    @csrf
                    
                    @foreach($questions as $index => $question)
                    <div id="question-{{ $index }}" class="question-panel {{ $index > 0 ? 'hidden' : '' }}">
                        <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 mb-6">
                            <!-- Question Header -->
                            <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <span class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold">
                                        Question {{ $index + 1 }}
                                    </span>
                                    <span class="text-gray-500 text-sm">of {{ $total_questions }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="text-amber-600 font-semibold">{{ $question->marks ?? 1 }} marks</span>
                                </div>
                            </div>
                            
                            <!-- Question Text -->
                            <div class="mb-8">
                                <p class="text-gray-900 text-lg leading-relaxed font-medium">{{ $question->question }}</p>
                            </div>
                            
                            <!-- Options -->
                            @if($question->type === 'mcq')
                            <div class="space-y-3">
                                @foreach(['A', 'B', 'C', 'D'] as $optKey)
                                @php $optionField = 'option_' . strtolower($optKey); @endphp
                                @if($question->{$optionField})
                                <label class="group flex items-start p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all border-2 border-gray-200 hover:border-primary option-label" data-question="{{ $question->id }}">
                                    <input type="radio" 
                                           name="answers[{{ $question->id }}]" 
                                           value="{{ $optKey }}"
                                           class="mt-1 w-5 h-5 text-primary border-gray-300 focus:ring-primary answer-input"
                                           data-question-id="{{ $question->id }}"
                                           data-question-index="{{ $index }}"
                                           {{ ($answers[$question->id] ?? '') === $optKey ? 'checked' : '' }}>
                                    <span class="ml-4 text-gray-900 flex-1">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary text-white font-bold mr-3 text-sm">
                                            {{ $optKey }}
                                        </span>
                                        <span class="text-base">{{ $question->{$optionField} }}</span>
                                    </span>
                                </label>
                                @endif
                                @endforeach
                            </div>
                            @endif

                            <!-- Navigation -->
                            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                <button type="button" onclick="goToQuestion({{ $index - 1 }})" 
                                        class="flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium {{ $index === 0 ? 'invisible' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </button>
                                
                                @if($index === $total_questions - 1)
                                <button type="submit" id="submit-btn" 
                                        class="flex items-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-bold shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit Exam
                                </button>
                                @else
                                <button type="button" onclick="goToQuestion({{ $index + 1 }})" 
                                        class="flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition-all font-medium shadow-md">
                                    Next
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </div>
</div>

<script>
let remainingTime = {{ $remaining_time }};
let currentQuestion = 0;
const totalQuestions = {{ $total_questions }};
const attemptId = {{ $attempt->id }};
const saveAnswerUrl = '{{ route("student.exams.save-answer", $attempt) }}';
const csrfToken = '{{ csrf_token() }}';
let answeredCount = {{ count($answers) }};

updateCounts();

function updateTimer() {
    if (remainingTime <= 0) {
        showTimeUpModal();
        setTimeout(() => document.getElementById('exam-form').submit(), 2000);
        return;
    }
    
    remainingTime--;
    const minutes = Math.floor(remainingTime / 60);
    const seconds = remainingTime % 60;
    const timerEl = document.getElementById('timer');
    timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    if (remainingTime <= 60) {
        timerEl.className = 'text-4xl font-mono font-bold text-red-600 animate-pulse';
    } else if (remainingTime <= 300) {
        timerEl.className = 'text-4xl font-mono font-bold text-amber-600';
    }
}

setInterval(updateTimer, 1000);
updateTimer();

function goToQuestion(index) {
    if (index < 0 || index >= totalQuestions) return;
    document.querySelectorAll('.question-panel').forEach((el, i) => {
        el.classList.toggle('hidden', i !== index);
    });
    currentQuestion = index;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateCounts() {
    answeredCount = document.querySelectorAll('.answer-input:checked').length;
    document.getElementById('answered-count').textContent = answeredCount;
    document.getElementById('unanswered-count').textContent = totalQuestions - answeredCount;
    document.getElementById('progress-text').textContent = `${answeredCount}/${totalQuestions}`;
    document.getElementById('progress-bar').style.width = `${(answeredCount / totalQuestions) * 100}%`;
}

document.querySelectorAll('.answer-input').forEach(input => {
    input.addEventListener('change', function() {
        const questionId = this.dataset.questionId;
        const questionIndex = this.dataset.questionIndex;
        const answer = this.value;
        
        document.getElementById(`nav-${questionIndex}`).className = 'aspect-square rounded-lg text-sm font-bold transition-all bg-green-500 text-white';
        updateCounts();
        
        const label = this.closest('.option-label');
        label.style.borderColor = 'var(--color-primary)';
        
        fetch(saveAnswerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ question_id: questionId, answer: answer })
        }).then(res => res.json()).then(data => {
            if (data.remaining_time) remainingTime = data.remaining_time;
        }).catch(console.error);
    });
});

function showTimeUpModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 shadow-2xl">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Time's Up!</h3>
                <p class="text-gray-600">Your exam is being submitted automatically...</p>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

window.onbeforeunload = () => "Are you sure you want to leave?";

document.getElementById('exam-form').onsubmit = function() {
    window.onbeforeunload = null;
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 shadow-2xl">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-full mb-4">
                    <svg class="w-10 h-10 text-primary animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Submitting...</h3>
                <p class="text-gray-600">Please wait</p>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
};

document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') goToQuestion(currentQuestion - 1);
    else if (e.key === 'ArrowRight') goToQuestion(currentQuestion + 1);
});
</script>
@endsection
