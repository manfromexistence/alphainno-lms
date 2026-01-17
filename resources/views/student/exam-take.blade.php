@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900" id="exam-app">
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Header with Timer -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-4 mb-6 sticky top-0 z-50">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $exam->name }}</h1>
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
                
                <p class="text-white text-lg mb-6">{{ $question->question }}</p>
                
                @if($question->type === 'mcq')
                <div class="space-y-3">
                    @foreach(['A', 'B', 'C', 'D'] as $optKey)
                    @php $optionField = 'option_' . strtolower($optKey); @endphp
                    @if($question->{$optionField})
                    <label class="flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition option-label" data-question="{{ $question->id }}">
                        <input type="radio" 
                               name="answers[{{ $question->id }}]" 
                               value="{{ $optKey }}"
                               class="w-4 h-4 text-indigo-600 answer-input"
                               data-question-id="{{ $question->id }}"
                               data-question-index="{{ $index }}"
                               {{ ($answers[$question->id] ?? '') === $optKey ? 'checked' : '' }}>
                        <span class="ml-3 text-white">
                            <span class="font-semibold mr-2">{{ $optKey }}.</span>
                            {{ $question->{$optionField} }}
                        </span>
                    </label>
                    @endif
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
    const csrfToken = '{{ csrf_token() }}';
    let answeredCount = {{ count($answers) }};

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
            timerEl.classList.remove('text-green-400');
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
</script>
@endsection
