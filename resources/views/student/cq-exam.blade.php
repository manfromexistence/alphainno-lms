@extends('layouts.admin')

@section('title', 'Take Exam - ' . $exam->title)

@section('content')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div class="min-h-screen bg-background py-6" id="exam-app">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header with Timer and Exam Info -->
        <div class="bg-card rounded-lg shadow-md p-6 mb-6 sticky top-0 z-50 border border-border">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-foreground">{{ $exam->title }}</h1>
                    <p class="text-muted-foreground text-sm mt-1">Creative Questions</p>
                    <p class="text-muted-foreground text-xs mt-1">Total Marks: {{ $exam->total_marks }} • Duration: {{ $exam->duration_minutes }} minutes</p>
                </div>
                <div class="text-right">
                    <div id="timer" class="text-4xl font-mono font-bold text-primary mb-1">--:--</div>
                    <p class="text-muted-foreground text-sm">Time Remaining</p>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm text-muted-foreground mb-2">
                    <span class="font-medium">Progress</span>
                    <span id="progress-text" class="font-medium">0/{{ $questions->count() }}</span>
                </div>
                <div class="w-full bg-secondary rounded-full h-3 overflow-hidden">
                    <div id="progress-bar" class="bg-primary h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Question Navigation Grid -->
        <div class="bg-card rounded-lg shadow-md p-6 mb-6 border border-border">
            <h3 class="text-foreground font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="question-nav-btn w-12 h-12 rounded-lg text-sm font-semibold transition-all duration-200 bg-secondary text-secondary-foreground hover:bg-accent border border-border"
                        title="Question {{ $index + 1 }}">
                    {{ $index + 1 }}
                </button>
                @endforeach
            </div>
            
            <!-- Legend -->
            <div class="flex items-center gap-6 mt-4 pt-4 border-t border-border">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-success"></div>
                    <span class="text-muted-foreground text-sm">Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-secondary border border-border"></div>
                    <span class="text-muted-foreground text-sm">Not Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary ring-2 ring-primary/30"></div>
                    <span class="text-muted-foreground text-sm">Current</span>
                </div>
            </div>
        </div>

        <!-- Questions Form -->
        <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}" enctype="multipart/form-data">
            @csrf
            
            @foreach($questions as $index => $question)
            <div id="question-{{ $index }}" 
                 class="question-panel bg-card rounded-lg shadow-md p-8 mb-6 border border-border transition-all duration-300 {{ $index > 0 ? 'hidden' : '' }}"
                 data-question-index="{{ $index }}">
                
                <!-- Question Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <span class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-semibold shadow">
                            Question {{ $index + 1 }} of {{ $questions->count() }}
                        </span>
                        <span class="text-muted-foreground text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1 text-warning" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            {{ $question->marks ?? 20 }} marks
                        </span>
                    </div>
                    <div id="question-status-{{ $index }}" class="text-sm font-medium">
                        <span class="text-muted-foreground flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Not Answered
                        </span>
                    </div>
                </div>
                
                <!-- Question Text -->
                <div class="bg-accent rounded-lg p-6 mb-6 border-l-4 border-primary">
                    <p class="text-foreground text-lg leading-relaxed">{!! nl2br(e($question->question_text ?? 'Question text not available')) !!}</p>
                </div>
                
                <!-- Text Editor for Answer -->
                <div class="mb-6">
                    <label class="block text-foreground text-sm font-semibold mb-3">
                        <svg class="w-4 h-4 inline mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Your Answer:
                    </label>
                    <textarea 
                        name="answers[{{ $question->id }}][text]" 
                        id="editor-{{ $index }}"
                        class="tinymce-editor w-full bg-card text-foreground rounded-lg p-4 min-h-[200px] border border-input focus:border-primary focus:ring-2 focus:ring-ring"
                        data-question-id="{{ $question->id }}"
                        data-question-index="{{ $index }}"
                        placeholder="Type your answer here..."></textarea>
                </div>
                
                <!-- Screenshot Upload -->
                <div class="mb-6">
                    <label class="block text-foreground text-sm font-semibold mb-3">
                        <svg class="w-4 h-4 inline mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Upload Screenshot (Optional):
                    </label>
                    <div class="border-2 border-dashed border-border rounded-lg p-6 text-center hover:border-primary transition-colors bg-secondary/50">
                        <input 
                            type="file" 
                            name="answers[{{ $question->id }}][screenshot]" 
                            id="screenshot-{{ $index }}"
                            accept="image/jpeg,image/png,image/jpg,application/pdf"
                            class="hidden screenshot-input"
                            data-question-index="{{ $index }}"
                            onchange="handleScreenshotUpload({{ $index }})">
                        <button type="button" 
                                onclick="document.getElementById('screenshot-{{ $index }}').click()" 
                                class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-colors inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose File
                        </button>
                        <p class="text-muted-foreground text-xs mt-2">JPG, PNG, or PDF • Max 5MB</p>
                        <div id="screenshot-preview-{{ $index }}" class="mt-3 hidden">
                            <div class="inline-flex items-center gap-2 bg-success/10 border border-success/20 px-4 py-2 rounded-lg">
                                <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-foreground text-sm" id="screenshot-name-{{ $index }}"></span>
                                <button type="button" 
                                        onclick="removeScreenshot({{ $index }})"
                                        class="text-destructive hover:opacity-80">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-border">
                    <button type="button" 
                            onclick="goToQuestion({{ $index - 1 }})" 
                            class="px-6 py-3 bg-secondary text-secondary-foreground rounded-lg hover:bg-accent transition-all duration-200 font-medium flex items-center gap-2 {{ $index === 0 ? 'invisible' : '' }}">
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
                                class="px-8 py-3 bg-success text-success-foreground rounded-lg hover:opacity-90 transition-all duration-200 font-bold shadow flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Exam
                        </button>
                        @else
                        <button type="button" 
                                onclick="goToQuestion({{ $index + 1 }})" 
                                class="px-6 py-3 bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-all duration-200 font-medium flex items-center gap-2">
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
<div id="submit-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-card rounded-lg shadow-2xl max-w-md w-full p-6 border border-border">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-warning/10 mb-4">
                <svg class="h-6 w-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-foreground mb-2">Submit Exam?</h3>
            <p class="text-muted-foreground mb-2">Are you sure you want to submit your exam?</p>
            <p id="unanswered-warning" class="text-warning text-sm mb-4"></p>
            <div class="flex gap-3 mt-6">
                <button type="button" 
                        onclick="closeSubmitModal()" 
                        class="flex-1 px-4 py-2 bg-secondary text-secondary-foreground rounded-lg hover:bg-accent transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" 
                        onclick="submitExam()" 
                        class="flex-1 px-4 py-2 bg-success text-success-foreground rounded-lg hover:opacity-90 transition-colors font-bold">
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
    const csrfToken = '{{ csrf_token() }}';
    let answeredCount = 0;
    let editorInstances = {};

    // Initialize TinyMCE
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '.tinymce-editor',
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap',
                'searchreplace', 'visualblocks', 'code',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | removeformat',
            setup: function(editor) {
                editor.on('init', function() {
                    const index = editor.targetElm.dataset.questionIndex;
                    editorInstances[index] = editor;
                });
                editor.on('change keyup', function() {
                    const index = editor.targetElm.dataset.questionIndex;
                    checkQuestionAnswered(index);
                });
            }
        });
    });

    // Timer
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
        
        if (remainingTime <= 60) {
            timerEl.className = 'text-4xl font-mono font-bold text-destructive mb-1';
        } else if (remainingTime <= 300) {
            timerEl.className = 'text-4xl font-mono font-bold text-warning mb-1';
        }
    }
    
    setInterval(updateTimer, 1000);
    updateTimer();

    // Navigation
    function goToQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        document.querySelectorAll('.question-panel').forEach((el, i) => {
            el.classList.toggle('hidden', i !== index);
        });
        
        document.querySelectorAll('.question-nav-btn').forEach((btn, i) => {
            if (i === index) {
                btn.classList.add('ring-2', 'ring-primary/30', 'bg-primary', 'text-primary-foreground');
                btn.classList.remove('bg-secondary', 'text-secondary-foreground');
            } else {
                btn.classList.remove('ring-2', 'ring-primary/30', 'bg-primary', 'text-primary-foreground');
                if (!btn.classList.contains('bg-success')) {
                    btn.classList.add('bg-secondary', 'text-secondary-foreground');
                }
            }
        });
        
        currentQuestion = index;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Check answered
    function checkQuestionAnswered(index) {
        const editor = editorInstances[index];
        const screenshotInput = document.getElementById(`screenshot-${index}`);
        
        const hasText = editor && editor.getContent().trim().length > 0;
        const hasScreenshot = screenshotInput && screenshotInput.files.length > 0;
        const isAnswered = hasText || hasScreenshot;
        
        const navBtn = document.getElementById(`nav-${index}`);
        const statusEl = document.getElementById(`question-status-${index}`);
        
        if (isAnswered) {
            navBtn.classList.remove('bg-secondary', 'text-secondary-foreground', 'bg-primary', 'text-primary-foreground');
            navBtn.classList.add('bg-success', 'text-success-foreground');
            
            if (statusEl) {
                statusEl.innerHTML = `
                    <span class="text-success flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Answered
                    </span>
                `;
            }
        } else {
            if (!navBtn.classList.contains('bg-primary')) {
                navBtn.classList.remove('bg-success', 'text-success-foreground');
                navBtn.classList.add('bg-secondary', 'text-secondary-foreground');
            }
            
            if (statusEl) {
                statusEl.innerHTML = `
                    <span class="text-muted-foreground flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Not Answered
                    </span>
                `;
            }
        }
        
        updateProgress();
    }

    // Update progress
    function updateProgress() {
        answeredCount = 0;
        for (let i = 0; i < totalQuestions; i++) {
            const editor = editorInstances[i];
            const screenshotInput = document.getElementById(`screenshot-${i}`);
            
            const hasText = editor && editor.getContent().trim().length > 0;
            const hasScreenshot = screenshotInput && screenshotInput.files.length > 0;
            
            if (hasText || hasScreenshot) answeredCount++;
        }
        
        document.getElementById('progress-text').textContent = `${answeredCount}/${totalQuestions}`;
        document.getElementById('progress-bar').style.width = `${(answeredCount / totalQuestions) * 100}%`;
    }

    // Screenshot handling
    function handleScreenshotUpload(index) {
        const input = document.getElementById(`screenshot-${index}`);
        const preview = document.getElementById(`screenshot-preview-${index}`);
        const nameEl = document.getElementById(`screenshot-name-${index}`);
        
        if (input.files.length > 0) {
            const file = input.files[0];
            
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, PNG, and PDF files are allowed');
                input.value = '';
                return;
            }
            
            nameEl.textContent = file.name;
            preview.classList.remove('hidden');
            checkQuestionAnswered(index);
        }
    }

    function removeScreenshot(index) {
        document.getElementById(`screenshot-${index}`).value = '';
        document.getElementById(`screenshot-preview-${index}`).classList.add('hidden');
        checkQuestionAnswered(index);
    }

    // Submit
    function confirmSubmit() {
        updateProgress();
        const unanswered = totalQuestions - answeredCount;
        const warningEl = document.getElementById('unanswered-warning');
        
        if (unanswered > 0) {
            warningEl.textContent = `You have ${unanswered} unanswered question${unanswered > 1 ? 's' : ''}.`;
        } else {
            warningEl.textContent = 'All questions answered!';
        }
        
        document.getElementById('submit-modal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submit-modal').classList.add('hidden');
    }

    function submitExam() {
        for (let index in editorInstances) {
            editorInstances[index].save();
        }
        window.onbeforeunload = null;
        document.getElementById('exam-form').submit();
    }

    function autoSubmitExam() {
        for (let index in editorInstances) {
            editorInstances[index].save();
        }
        window.onbeforeunload = null;
        alert('Time is up! Submitting exam...');
        document.getElementById('exam-form').submit();
    }

    // Prevent navigation
    window.onbeforeunload = () => "Are you sure you want to leave?";
    document.getElementById('exam-form').onsubmit = () => window.onbeforeunload = null;

    // Initialize
    goToQuestion(0);
</script>

<style>
    .question-panel {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Header with Timer and Exam Info -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6 sticky top-0 z-50">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $exam->name }}</h1>
                    <p class="text-gray-400 text-sm mt-1">Creative Questions</p>
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
                    <div id="progress-bar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
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
                        class="question-nav-btn w-12 h-12 rounded-lg text-sm font-semibold transition-all duration-200 transform hover:scale-105 bg-gray-700 text-gray-300 hover:bg-gray-600"
                        title="Question {{ $index + 1 }}">
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
                    <div class="w-8 h-8 rounded-lg bg-purple-600 ring-2 ring-purple-400"></div>
                    <span class="text-gray-400 text-sm">Current</span>
                </div>
            </div>
        </div>

        <!-- Questions Form -->
        <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}" enctype="multipart/form-data">
            @csrf
            
            @foreach($questions as $index => $question)
            <div id="question-{{ $index }}" 
                 class="question-panel bg-gray-800 rounded-lg shadow-lg p-8 mb-6 transition-all duration-300 {{ $index > 0 ? 'hidden' : '' }}"
                 data-question-index="{{ $index }}">
                
                <!-- Question Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <span class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
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
                        <span class="text-gray-500 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Not Answered
                        </span>
                    </div>
                </div>
                
                <!-- Question Text -->
                <div class="bg-gray-900 rounded-lg p-6 mb-6 border-l-4 border-purple-600">
                    <p class="text-white text-lg leading-relaxed">{!! nl2br(e($question->question)) !!}</p>
                </div>
                
                <!-- Text Editor for Answer -->
                <div class="mb-6">
                    <label class="block text-white text-sm font-semibold mb-3">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Your Answer:
                    </label>
                    <textarea 
                        name="answers[{{ $question->id }}][text]" 
                        id="editor-{{ $index }}"
                        class="tinymce-editor w-full bg-gray-700 text-white rounded-lg p-4 min-h-[200px] border border-gray-600 focus:border-purple-500 focus:ring-2 focus:ring-purple-500"
                        data-question-id="{{ $question->id }}"
                        data-question-index="{{ $index }}"
                        placeholder="Type your answer here..."></textarea>
                </div>
                
                <!-- Screenshot Upload -->
                <div class="mb-6">
                    <label class="block text-white text-sm font-semibold mb-3">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Upload Screenshot (Optional):
                    </label>
                    <div class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center hover:border-purple-500 transition-colors bg-gray-700/50">
                        <input 
                            type="file" 
                            name="answers[{{ $question->id }}][screenshot]" 
                            id="screenshot-{{ $index }}"
                            accept="image/jpeg,image/png,image/jpg,application/pdf"
                            class="hidden screenshot-input"
                            data-question-index="{{ $index }}"
                            onchange="handleScreenshotUpload({{ $index }})">
                        <button type="button" 
                                onclick="document.getElementById('screenshot-{{ $index }}').click()" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose File
                        </button>
                        <p class="text-gray-400 text-xs mt-2">JPG, PNG, or PDF • Max 5MB</p>
                        <div id="screenshot-preview-{{ $index }}" class="mt-3 hidden">
                            <div class="inline-flex items-center gap-2 bg-gray-800 px-4 py-2 rounded-lg">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white text-sm" id="screenshot-name-{{ $index }}"></span>
                                <button type="button" 
                                        onclick="removeScreenshot({{ $index }})"
                                        class="text-red-400 hover:text-red-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 font-medium flex items-center gap-2">
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
    const csrfToken = '{{ csrf_token() }}';
    let answeredCount = 0;
    let autoSaveTimeout = null;
    let tabSwitchCount = 0;
    let fullscreenExitCount = 0;
    let editorInstances = {};

    // Initialize TinyMCE for all textareas
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '.tinymce-editor',
            height: 300,
            menubar: false,
            skin: 'oxide-dark',
            content_css: 'dark',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; color: #fff; background-color: #1f2937; }',
            setup: function(editor) {
                editor.on('init', function() {
                    const index = editor.targetElm.dataset.questionIndex;
                    editorInstances[index] = editor;
                });
                editor.on('change keyup', function() {
                    const index = editor.targetElm.dataset.questionIndex;
                    checkQuestionAnswered(index);
                });
            }
        });
    });

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
                btn.classList.add('ring-2', 'ring-purple-400', 'bg-purple-600');
            } else {
                btn.classList.remove('ring-2', 'ring-purple-400', 'bg-purple-600');
            }
        });
        
        currentQuestion = index;
        
        // Scroll to top smoothly
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Check if question is answered
    function checkQuestionAnswered(index) {
        const editor = editorInstances[index];
        const screenshotInput = document.getElementById(`screenshot-${index}`);
        
        const hasText = editor && editor.getContent().trim().length > 0;
        const hasScreenshot = screenshotInput && screenshotInput.files.length > 0;
        
        const isAnswered = hasText || hasScreenshot;
        
        const navBtn = document.getElementById(`nav-${index}`);
        const statusEl = document.getElementById(`question-status-${index}`);
        
        if (isAnswered) {
            navBtn.classList.remove('bg-gray-700', 'text-gray-300');
            navBtn.classList.add('bg-green-600', 'text-white', 'shadow-lg');
            
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
        } else {
            navBtn.classList.remove('bg-green-600', 'text-white', 'shadow-lg');
            navBtn.classList.add('bg-gray-700', 'text-gray-300');
            
            if (statusEl) {
                statusEl.innerHTML = `
                    <span class="text-gray-500 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Not Answered
                    </span>
                `;
            }
        }
        
        updateProgress();
    }

    // Update Progress
    function updateProgress() {
        answeredCount = 0;
        for (let i = 0; i < totalQuestions; i++) {
            const editor = editorInstances[i];
            const screenshotInput = document.getElementById(`screenshot-${i}`);
            
            const hasText = editor && editor.getContent().trim().length > 0;
            const hasScreenshot = screenshotInput && screenshotInput.files.length > 0;
            
            if (hasText || hasScreenshot) {
                answeredCount++;
            }
        }
        
        document.getElementById('progress-text').textContent = `${answeredCount}/${totalQuestions}`;
        const percentage = (answeredCount / totalQuestions) * 100;
        document.getElementById('progress-bar').style.width = `${percentage}%`;
    }

    // Handle Screenshot Upload
    function handleScreenshotUpload(index) {
        const input = document.getElementById(`screenshot-${index}`);
        const preview = document.getElementById(`screenshot-preview-${index}`);
        const nameEl = document.getElementById(`screenshot-name-${index}`);
        
        if (input.files.length > 0) {
            const file = input.files[0];
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, PNG, and PDF files are allowed');
                input.value = '';
                return;
            }
            
            nameEl.textContent = file.name;
            preview.classList.remove('hidden');
            checkQuestionAnswered(index);
        }
    }

    // Remove Screenshot
    function removeScreenshot(index) {
        const input = document.getElementById(`screenshot-${index}`);
        const preview = document.getElementById(`screenshot-preview-${index}`);
        
        input.value = '';
        preview.classList.add('hidden');
        checkQuestionAnswered(index);
    }

    // Submit Confirmation
    function confirmSubmit() {
        updateProgress();
        const unanswered = totalQuestions - answeredCount;
        const warningEl = document.getElementById('unanswered-warning');
        
        if (unanswered > 0) {
            warningEl.textContent = `You have ${unanswered} unanswered question${unanswered > 1 ? 's' : ''}.`;
            warningEl.classList.remove('text-green-400');
            warningEl.classList.add('text-yellow-400');
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
        // Sync all TinyMCE editors before submit
        for (let index in editorInstances) {
            editorInstances[index].save();
        }
        
        window.onbeforeunload = null;
        document.getElementById('exam-form').submit();
    }

    function autoSubmitExam() {
        // Sync all TinyMCE editors before submit
        for (let index in editorInstances) {
            editorInstances[index].save();
        }
        
        window.onbeforeunload = null;
        alert('Time is up! Your exam will be submitted automatically.');
        document.getElementById('exam-form').submit();
    }

    // Prevent Accidental Navigation
    window.onbeforeunload = function(e) {
        e.preventDefault();
        return "Are you sure you want to leave? Your progress may be lost.";
    };
    
    document.getElementById('exam-form').onsubmit = function() {
        window.onbeforeunload = null;
    };

    // Anti-Cheating: Fullscreen Detection
    function requestFullscreen() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(err => {
                console.log('Fullscreen request failed:', err);
            });
        } else if (elem.webkitRequestFullscreen) { // Safari
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { // IE11
            elem.msRequestFullscreen();
        }
    }

    function detectFullscreenExit() {
        if (!document.fullscreenElement && 
            !document.webkitFullscreenElement && 
            !document.msFullscreenElement) {
            
            fullscreenExitCount++;
            
            if (fullscreenExitCount === 1) {
                alert('Warning: You have exited fullscreen mode. This has been recorded. Please return to fullscreen.');
                requestFullscreen();
            } else if (fullscreenExitCount <= 3) {
                alert(`Warning ${fullscreenExitCount}: Fullscreen exit detected and recorded. Please stay in fullscreen mode.`);
                requestFullscreen();
            }
        }
    }

    // Anti-Cheating: Tab Switch Detection
    function detectTabSwitch() {
        if (document.hidden) {
            tabSwitchCount++;
            
            setTimeout(() => {
                if (!document.hidden) {
                    if (tabSwitchCount === 1) {
                        alert('Warning: Tab switch detected and recorded. Please stay on this exam tab.');
                    } else if (tabSwitchCount <= 3) {
                        alert(`Warning ${tabSwitchCount}: Multiple tab switches detected. This behavior is being monitored.`);
                    }
                }
            }, 500);
        }
    }

    // Event Listeners for Anti-Cheating
    document.addEventListener('fullscreenchange', detectFullscreenExit);
    document.addEventListener('webkitfullscreenchange', detectFullscreenExit);
    document.addEventListener('msfullscreenchange', detectFullscreenExit);
    document.addEventListener('visibilitychange', detectTabSwitch);

    // Initialize
    goToQuestion(0);
    
    // Request fullscreen on page load
    setTimeout(() => {
        requestFullscreen();
    }, 1000);

</script>

<style>
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
    
    /* TinyMCE dark theme adjustments */
    .tox .tox-toolbar,
    .tox .tox-toolbar__overflow,
    .tox .tox-toolbar__primary {
        background-color: #374151 !important;
    }
    
    .tox .tox-edit-area__iframe {
        background-color: #1f2937 !important;
    }
</style>
@endsection
