<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <a href="{{ route('siswa.courses.learn', $attempt->quiz->course) }}"
            class="text-sm text-gray-600 hover:underline">&larr; Kembali</a>
        <h1 class="text-2xl font-bold mt-3 mb-4">Hasil Kuis</h1>

        @php
            $passed = $attempt->score >= 70;
        @endphp

        <div class="bg-white shadow rounded p-5">
            <div class="text-gray-600">Kelas</div>
            <div class="font-semibold">{{ $attempt->quiz->course->title }}</div>

            <div class="text-gray-600 mt-3">Kuis</div>
            <div class="font-semibold">{{ $attempt->quiz->title }}</div>

            <div class="text-gray-600 mt-3">Skor</div>
            <div class="text-3xl font-bold {{ $passed ? 'text-green-700' : 'text-red-700' }}">{{ $attempt->score }}%
            </div>

            <div class="mt-3 text-sm font-semibold {{ $passed ? 'text-green-700' : 'text-red-700' }}">
                {{ $passed ? 'Lulus' : 'Gagal' }}
            </div>

            @if ($passed && !empty($attempt->certificate_path))
                <a class="mt-5 inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                    href="{{ asset('storage/' . ltrim($attempt->certificate_path, '/')) }}" download>Unduh Sertifikat
                    PDF</a>
            @else
                <div class="mt-5 text-gray-600 text-sm">Sertifikat belum tersedia.</div>
            @endif
        </div>
    </div>
    </x-layouts.dashboard>
