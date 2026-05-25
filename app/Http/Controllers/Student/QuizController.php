<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $quizAttempts = $user->quizAttempts()
            ->with('quiz.course')
            ->latest()
            ->get();

        return view('student.quizzes.index', compact('user', 'quizAttempts'));
    }
}
