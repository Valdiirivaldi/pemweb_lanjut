<x-layouts.dashboard :user="$user">
    <div class="max-w-3xl mx-auto p-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('siswa.courses.learn', $quiz->course) }}"
                class="text-sm text-gray-600 hover:underline">&larr; Kembali</a>
            <div class="text-sm font-semibold text-indigo-700">{{ $quiz->course->title }}</div>
        </div>

        <h1 class="text-2xl font-bold mt-3 mb-6">{{ $quiz->title }}</h1>

        <div class="fixed top-4 right-4 z-50 bg-indigo-600 text-white px-4 py-2 rounded shadow">
            <div class="text-xs opacity-90">Timer</div>
            <div id="timer" class="text-xl font-mono">00:00</div>
        </div>

        <form method="POST" action="{{ route('siswa.quizzes.submit', $quiz) }}" id="quizForm"
            class="bg-white shadow rounded p-5">
            @csrf
            @php
                $total = $quiz->questions->count();
            @endphp

            @forelse($quiz->questions as $i => $question)
                <div class="mb-6">
                    <div class="font-medium mb-2">{{ $i + 1 }}. {{ $question->question_text }}</div>

                    @php $qId = $question->id; @endphp
                    <div class="space-y-2">
                        @foreach (['A', 'B', 'C', 'D'] as $opt)
                            @php
                                $label = $opt;
                                $value = $opt;
                                $text = match ($opt) {
                                    'A' => $question->option_a,
                                    'B' => $question->option_b,
                                    'C' => $question->option_c,
                                    'D' => $question->option_d,
                                };
                            @endphp
                            <label class="flex items-center gap-2 p-3 border rounded cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="answers[{{ $qId }}]" value="{{ $value }}"
                                    required>
                                <span class="font-semibold">{{ $label }}.</span>
                                <span class="text-gray-800">{{ $text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-gray-600">Soal belum tersedia.</div>
            @endforelse

            <div class="flex items-center justify-between mt-2">
                <div class="text-sm text-gray-600">Total soal: {{ $total }}</div>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
            </div>
        </form>
    </div>

    <script>
        (function() {
            const minutes = {{ (int) $quiz->time_limit }};
            let seconds = minutes * 60;

            const timerEl = document.getElementById('timer');
            const form = document.getElementById('quizForm');
            let submitted = false;

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function render() {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                timerEl.textContent = pad(m) + ':' + pad(s);
            }

            function submitIfNeeded() {
                if (submitted) return;
                submitted = true;
                form.submit();
            }

            render();
            const t = setInterval(() => {
                seconds--;
                if (seconds <= 0) {
                    clearInterval(t);
                    timerEl.textContent = '00:00';
                    submitIfNeeded();
                    return;
                }
                render();
            }, 1000);
        })();
    </script>
</x-layouts.dashboard>
