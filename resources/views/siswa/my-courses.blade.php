@extends('layouts.dashboard')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Kelas Saya</h1>

        @if ($courses->isEmpty())
            <div class="bg-white shadow rounded p-4 text-gray-600">
                Belum ada kelas yang diberikan akses untuk Anda.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($courses as $course)
                    <div class="bg-white shadow rounded p-4">
                        <h2 class="font-semibold text-lg">{{ $course->title }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Tentor: {{ $course->tentor?->name }}</p>
                        <div class="text-sm text-gray-600 mt-2">
                            Modul: {{ $course->modules_count ?? 0 }} • Kuis: {{ $course->quizzes_count ?? 0 }}
                        </div>

                        <a href="{{ route('siswa.courses.learn', $course->id) }}" class="btn btn-primary px-4 py-2 mt-4">
                            Masuk Kelas
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
