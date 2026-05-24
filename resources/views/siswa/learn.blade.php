<x-app-layout>
    <div class="container">
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Ruang Belajar: ' . $course->title) }}
                </h2>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-6xl mx-auto">
                <a href="{{ route('siswa.my-courses.index') }}" class="text-sm text-gray-600 hover:underline">&larr;
                    Kembali</a>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                    <div class="bg-white shadow rounded p-4">
                        <h2 class="font-semibold text-lg mb-3">Materi (Modul)</h2>

                        @forelse($course->modules as $module)
                            <div class="mb-5">
                                <h3 class="font-medium">{{ $module->title }}</h3>

                                <div class="mt-2 text-sm text-gray-600">Video:</div>

                                @if (!empty($module->video_url))
                                    <div class="mt-2">
                                        <iframe class="w-full aspect-video rounded" src="{{ $module->video_url }}"
                                            title="{{ $module->title }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                @endif

                                @if (!empty($module->pdf_path))
                                    <a class="mt-3 inline-block px-3 py-2 bg-gray-900 text-white text-sm rounded"
                                        href="{{ asset('storage/' . ltrim($module->pdf_path, '/')) }}" download>Unduh
                                        PDF</a>
                                @endif
                            </div>
                        @empty
                            <div class="text-gray-600">Belum ada modul.</div>
                        @endforelse
                    </div>

                    <div class="bg-white shadow rounded p-4">
                        <h2 class="font-semibold text-lg mb-3">Kuis</h2>

                        @forelse($course->quizzes as $quiz)
                            <div class="border rounded p-3 mb-3">
                                <div class="font-medium">{{ $quiz->title }}</div>
                                <div class="text-sm text-gray-600 mt-1">Durasi: {{ $quiz->time_limit }} menit</div>
                                <a href="{{ route('siswa.quizzes.show', $quiz) }}"
                                    class="inline-block mt-3 px-3 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Mulai
                                    Kuis</a>
                            </div>
                        @empty
                            <div class="text-gray-600">Belum ada kuis untuk kelas ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
