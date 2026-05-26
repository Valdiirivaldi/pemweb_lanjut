@extends('layouts.dashboard')

@section('title', 'Add Question - Eduria')
@section('page-title', 'Add New Question')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}"
       class="nav-link {{ request()->routeIs('tentor.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('tentor.courses.index') }}"
       class="nav-link {{ request()->routeIs('tentor.courses.*') ? 'active' : '' }}">
        <i class="fas fa-book"></i>My Courses
    </a>
    <a href="{{ route('tentor.modules.index') }}"
       class="nav-link {{ request()->routeIs('tentor.modules.*') ? 'active' : '' }}">
        <i class="fas fa-layer-group"></i>Modules
    </a>
    <a href="{{ route('tentor.quizzes.index') }}"
       class="nav-link {{ request()->routeIs('tentor.quizzes.*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i>Quizzes
    </a>
    <a href="{{ route('tentor.students.index') }}"
       class="nav-link {{ request()->routeIs('tentor.students.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>Participants
    </a>
    <a href="{{ route('profile') }}"
       class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .form-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4f8;
        padding: 20px 24px;
        font-weight: 700;
        color: #1e3c72;
    }
    .form-card .card-body {
        padding: 24px;
    }
    .form-input {
        height: 48px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding-left: 16px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78,115,223,0.12);
        outline: none;
    }
    textarea.form-input {
        height: auto;
        resize: vertical;
        min-height: 100px;
        padding-top: 12px;
    }
    .form-label-custom {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.85rem;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-label-custom .label-icon {
        color: #4e73df;
    }
    .btn-primary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 700;
        font-size: 0.95rem;
    }
    .btn-secondary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 600;
    }
    .type-selector {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .type-selector .type-option {
        flex: 1;
        min-width: 140px;
    }
    .type-selector .type-option input {
        display: none;
    }
    .type-selector .type-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-align: center;
    }
    .type-selector .type-option label:hover {
        border-color: #4e73df;
        background: rgba(78,115,223,0.04);
    }
    .type-selector .type-option input:checked + label {
        border-color: #4e73df;
        background: rgba(78,115,223,0.08);
        box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
    }
    .type-selector .type-option label .type-icon {
        font-size: 1.5rem;
        color: #4e73df;
    }
    .type-selector .type-option label .type-name {
        font-weight: 700;
        color: #1e3c72;
        font-size: 0.85rem;
    }
    .type-selector .type-option label .type-desc {
        font-size: 0.72rem;
        color: #a0aec0;
    }
    .option-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        animation: slideIn 0.25s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .option-row .option-key {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #f0f4ff;
        color: #4e73df;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .option-row .form-input {
        flex: 1;
        height: 42px;
    }
    .option-row .correct-mark {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.78rem;
        cursor: pointer;
        padding: 4px 10px;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        user-select: none;
    }
    .option-row .correct-mark:hover {
        border-color: #10b981;
        background: rgba(16,185,129,0.04);
    }
    .option-row .correct-mark.marked {
        border-color: #10b981;
        background: rgba(16,185,129,0.1);
        color: #10b981;
        font-weight: 600;
    }
    .option-row .correct-mark.marked-multi {
        border-color: #4e73df;
        background: rgba(78,115,223,0.1);
        color: #4e73df;
        font-weight: 600;
    }
    .option-row .correct-mark input {
        display: none;
    }
    .option-row .btn-remove-option {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1.5px solid #fee2e2;
        background: #fff;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .option-row .btn-remove-option:hover {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-add-option {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 2px dashed #cbd5e0;
        background: transparent;
        color: #4e73df;
        cursor: pointer;
        transition: all 0.25s ease;
        width: 100%;
        text-align: center;
    }
    .btn-add-option:hover {
        border-color: #4e73df;
        background: rgba(78,115,223,0.04);
        color: #224abe;
    }
    .tf-note {
        font-size: 0.82rem;
        color: #a0aec0;
        padding: 10px 14px;
        background: #fffbeb;
        border-radius: 10px;
        border: 1px solid #fde68a;
        margin-bottom: 12px;
    }
    .tf-note i {
        color: #f59e0b;
        margin-right: 6px;
    }
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <a href="{{ route('tentor.quizzes.questions.index', $quiz->id) }}"
               class="back-link d-inline-flex align-items-center gap-2 text-decoration-none mb-3"
               style="color: #718096; font-size: 0.88rem; font-weight: 500; padding: 8px 16px; border-radius: 10px; transition: all 0.25s ease;">
                <i class="fas fa-arrow-left"></i> Back to Questions
            </a>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle me-1"></i> Please fix the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-plus-circle me-2" style="color: #4e73df;"></i>
                    Add Question to: {{ $quiz->title }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.quizzes.questions.store', $quiz->id) }}" id="questionForm">
                        @csrf

                        {{-- Question Text --}}
                        <div class="mb-4">
                            <label for="question_text" class="form-label-custom">
                                <i class="fas fa-question-circle label-icon"></i>Question Text
                            </label>
                            <textarea class="form-control form-input @error('question_text') is-invalid @enderror"
                                      id="question_text" name="question_text"
                                      placeholder="Enter the question..." required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Question Type --}}
                        <div class="mb-4">
                            <label class="form-label-custom">
                                <i class="fas fa-tag label-icon"></i>Question Type
                            </label>
                            <div class="type-selector" id="typeSelector">
                                <div class="type-option">
                                    <input type="radio" name="type" id="type-single" value="single"
                                        {{ old('type', 'single') === 'single' ? 'checked' : '' }}>
                                    <label for="type-single">
                                        <i class="fas fa-dot-circle type-icon"></i>
                                        <span class="type-name">Single Choice</span>
                                        <span class="type-desc">One correct answer</span>
                                    </label>
                                </div>
                                <div class="type-option">
                                    <input type="radio" name="type" id="type-multiple" value="multiple"
                                        {{ old('type') === 'multiple' ? 'checked' : '' }}>
                                    <label for="type-multiple">
                                        <i class="fas fa-check-double type-icon"></i>
                                        <span class="type-name">Multiple Choice</span>
                                        <span class="type-desc">Multiple correct answers</span>
                                    </label>
                                </div>
                                <div class="type-option">
                                    <input type="radio" name="type" id="type-truefalse" value="true_false"
                                        {{ old('type') === 'true_false' ? 'checked' : '' }}>
                                    <label for="type-truefalse">
                                        <i class="fas fa-toggle-on type-icon"></i>
                                        <span class="type-name">True / False</span>
                                        <span class="type-desc">Two-option question</span>
                                    </label>
                                </div>
                            </div>
                            @error('type')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- True/False Note --}}
                        <div class="tf-note" id="tfNote" style="{{ old('type') === 'true_false' ? '' : 'display: none;' }}">
                            <i class="fas fa-info-circle"></i>
                            True/False questions automatically use "True" and "False" as the only options.
                        </div>

                        {{-- Options --}}
                        <div class="mb-4">
                            <label class="form-label-custom">
                                <i class="fas fa-list label-icon"></i>Answer Options
                                <span class="text-muted" style="font-weight: 400; font-size: 0.78rem;">(min. 2)</span>
                            </label>

                            <div id="optionsContainer">
                                {{-- Options will be rendered here by JS --}}
                            </div>

                            <input type="hidden" id="optionsData" name="options">

                            <button type="button" class="btn-add-option mt-2" id="addOptionBtn" style="display: none;">
                                <i class="fas fa-plus me-1"></i> Add Option
                            </button>

                            @error('options')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            @error('correct_options')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex gap-3 mt-4 pt-3 border-top">
                            <a href="{{ route('tentor.quizzes.questions.index', $quiz->id) }}"
                               class="btn btn-outline-secondary btn-secondary-custom px-4">
                                <i class="fas fa-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
                                <i class="fas fa-check-circle me-2"></i>Save Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function() {
    const LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    let options = [];
    let correctOptions = [];
    let currentType = '{{ old('type', 'single') }}';

    const optionsContainer = document.getElementById('optionsContainer');
    const addOptionBtn = document.getElementById('addOptionBtn');
    const optionsData = document.getElementById('optionsData');
    const tfNote = document.getElementById('tfNote');

    // ── Initialize from old input ──
    function initFromOld() {
        @php
            $oldOptions = old('options', []);
        @endphp
        const oldOpts = @json($oldOptions);
        const oldCorrect = @json(old('correct_options', []));

        if (Object.keys(oldOpts).length > 0) {
            for (const [key, text] of Object.entries(oldOpts)) {
                options.push({ key, text });
            }
            correctOptions = [...oldCorrect];
        } else {
            // Default: start with 4 options
            options = [
                { key: 'A', text: '' },
                { key: 'B', text: '' },
                { key: 'C', text: '' },
                { key: 'D', text: '' },
            ];
            correctOptions = [];
        }
    }

    // ── Get next available letter key ──
    function nextKey() {
        const used = new Set(options.map(o => o.key));
        for (let i = 0; i < LETTERS.length; i++) {
            if (!used.has(LETTERS[i])) return LETTERS[i];
        }
        return 'Z';
    }

    // ── Add option ──
    function addOption(key, text) {
        if (!key) key = nextKey();
        if (options.find(o => o.key === key)) return;
        options.push({ key, text: text || '' });
        renderOptions();
    }

    // ── Remove option ──
    function removeOption(key) {
        if (options.length <= 2) return;
        options = options.filter(o => o.key !== key);
        correctOptions = correctOptions.filter(c => c !== key);
        renderOptions();
    }

    // ── Toggle correct (single mode) ──
    function setCorrectSingle(key) {
        correctOptions = [key];
        renderOptions();
    }

    // ── Toggle correct (multiple mode) ──
    function toggleCorrectMultiple(key) {
        const idx = correctOptions.indexOf(key);
        if (idx === -1) {
            correctOptions.push(key);
        } else {
            correctOptions.splice(idx, 1);
        }
        renderOptions();
    }

    // ── Generate True/False options ──
    function setTrueFalse() {
        options = [
            { key: 'A', text: 'True' },
            { key: 'B', text: 'False' },
        ];
        // Keep only valid correct options
        correctOptions = correctOptions.filter(c => c === 'A' || c === 'B');
        if (correctOptions.length === 0) correctOptions = ['A'];
        renderOptions();
    }

    // ── Handle type change ──
    function onTypeChange(type) {
        currentType = type;

        if (type === 'true_false') {
            tfNote.style.display = '';
            addOptionBtn.style.display = 'none';
            setTrueFalse();
        } else {
            tfNote.style.display = 'none';
            addOptionBtn.style.display = '';
            // Ensure minimum 2 options
            if (options.length < 2) {
                while (options.length < 2) {
                    addOption(nextKey(), '');
                }
            }
            // For single, ensure only 1 correct
            if (type === 'single' && correctOptions.length > 1) {
                correctOptions = [correctOptions[0]];
            }
            renderOptions();
        }
    }

    // ── Render ──
    function renderOptions() {
        const isTF = currentType === 'true_false';
        const isMulti = currentType === 'multiple';

        let html = '';
        let hasCorrect = false;

        options.forEach((opt, idx) => {
            const isCorrect = correctOptions.includes(opt.key);
            if (isCorrect) hasCorrect = true;

            const markedClass = isMulti
                ? (isCorrect ? 'correct-mark marked-multi' : 'correct-mark')
                : (isCorrect ? 'correct-mark marked' : 'correct-mark');

            html += `
                <div class="option-row" data-key="${opt.key}">
                    <div class="option-key">${opt.key}</div>
                    <input type="text"
                           class="form-control form-input option-input"
                           data-key="${opt.key}"
                           value="${escapeHtml(opt.text)}"
                           placeholder="Option ${opt.key}"
                           ${isTF ? 'readonly' : ''}>
                    <div class="${markedClass}" data-key="${opt.key}">
                        <input type="${isMulti ? 'checkbox' : 'radio'}"
                               name="_correct_ui"
                               value="${opt.key}"
                               ${isCorrect ? 'checked' : ''}
                               ${isTF ? '' : ''}>
                        <span>${isCorrect ? (isMulti ? '✓ Correct' : '✓ Correct') : 'Correct'}</span>
                    </div>
                    ${!isTF && options.length > 2
                        ? `<button type="button" class="btn-remove-option" data-key="${opt.key}" title="Remove option">
                                <i class="fas fa-times"></i>
                           </button>`
                        : ''}
                </div>
            `;
        });

        optionsContainer.innerHTML = html || '<div class="text-muted small py-2">No options yet.</div>';

        // If no correct option selected, auto-select first
        if (!hasCorrect && options.length > 0) {
            if (isMulti) {
                // Don't auto-select for multiple
            } else {
                correctOptions = [options[0].key];
                renderOptions();
                return;
            }
        }

        // ── Sync hidden input ──
        syncHiddenInput();

        // ── Wire events ──
        document.querySelectorAll('.option-input').forEach(inp => {
            inp.addEventListener('input', function() {
                const key = this.getAttribute('data-key');
                const opt = options.find(o => o.key === key);
                if (opt) opt.text = this.value;
                syncHiddenInput();
            });
        });

        document.querySelectorAll('.correct-mark').forEach(el => {
            el.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                if (isMulti) {
                    toggleCorrectMultiple(key);
                } else {
                    setCorrectSingle(key);
                }
            });
        });

        document.querySelectorAll('.btn-remove-option').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                removeOption(key);
            });
        });
    }

    function syncHiddenInput() {
        const obj = {};
        options.forEach(o => { obj[o.key] = o.text; });
        optionsData.value = JSON.stringify(obj);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ── Type selector event ──
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            onTypeChange(this.value);
        });
    });

    // ── Add option button ──
    addOptionBtn.addEventListener('click', function() {
        const key = nextKey();
        if (!key) return;
        options.push({ key, text: '' });
        renderOptions();
        // Scroll to new option
        const container = optionsContainer;
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    // ── Before submit: build correct_options array ──
    document.getElementById('questionForm').addEventListener('submit', function() {
        // Remove old inputs
        document.querySelectorAll('.correct-option-input').forEach(el => el.remove());

        // Validate
        if (options.length < 2) {
            alert('Please add at least 2 options.');
            return false;
        }
        if (correctOptions.length === 0) {
            alert('Please select at least one correct answer.');
            return false;
        }

        // Add hidden inputs for correct_options
        correctOptions.forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'correct_options[]';
            input.value = key;
            input.className = 'correct-option-input';
            this.appendChild(input);
        });

        // Add hidden inputs for options
        options.forEach(o => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'options[' + o.key + ']';
            input.value = o.text;
            input.className = 'correct-option-input';
            this.appendChild(input);
        });
    });

    // ── Init ──
    initFromOld();
    onTypeChange(currentType);
})();
</script>
@endpush
