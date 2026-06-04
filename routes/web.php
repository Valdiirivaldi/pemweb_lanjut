<?php

use App\Http\Controllers\Admin\AdminEnrollmentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tentor\CourseController;
use App\Http\Controllers\Tentor\DashboardController as TentorDashboardController;
use App\Http\Controllers\Tentor\ModuleController;
use App\Http\Controllers\Tentor\QuizController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Tentor\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('tentor')->name('tentor.')->middleware('role:tentor')->group(function () {
        Route::get('/dashboard', [TentorDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/courses', [CourseController::class, 'index'])
            ->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])
            ->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])
            ->name('courses.store');
        Route::get('/courses/{course}', [CourseController::class, 'show'])
            ->name('courses.show');
        Route::get('/modules/create', [ModuleController::class, 'create'])
            ->name('modules.create');
        Route::post('/modules', [ModuleController::class, 'store'])
            ->name('modules.store');
        Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])
            ->name('modules.edit');
        Route::put('/modules/{module}', [ModuleController::class, 'update'])
            ->name('modules.update');
        Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])
            ->name('modules.destroy');
        Route::get('/modules', [ModuleController::class, 'index'])
            ->name('modules.index');
        Route::get('/quizzes', [QuizController::class, 'index'])
            ->name('quizzes.index');
        Route::get('/quizzes/create', [QuizController::class, 'create'])
            ->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'store'])
            ->name('quizzes.store');
        Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])
            ->name('quizzes.edit');
        Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])
            ->name('quizzes.update');
        Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])
            ->name('quizzes.destroy');
        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        Route::get('/quizzes/{quiz}/questions', [\App\Http\Controllers\Tentor\QuestionController::class, 'index'])
            ->name('quizzes.questions.index');
        Route::get('/quizzes/{quiz}/questions/create', [\App\Http\Controllers\Tentor\QuestionController::class, 'create'])
            ->name('quizzes.questions.create');
        Route::post('/quizzes/{quiz}/questions', [\App\Http\Controllers\Tentor\QuestionController::class, 'store'])
            ->name('quizzes.questions.store');
        Route::get('/quizzes/{quiz}/questions/{question}/edit', [\App\Http\Controllers\Tentor\QuestionController::class, 'edit'])
            ->name('quizzes.questions.edit');
        Route::put('/quizzes/{quiz}/questions/{question}', [\App\Http\Controllers\Tentor\QuestionController::class, 'update'])
            ->name('quizzes.questions.update');
        Route::delete('/quizzes/{quiz}/questions/{question}', [\App\Http\Controllers\Tentor\QuestionController::class, 'destroy'])
            ->name('quizzes.questions.destroy');

        Route::get('/quizzes/{quiz}/attempts', [QuizController::class, 'attemptsIndex'])
            ->name('quizzes.attempts.index');
        Route::get('/quizzes/{quiz}/attempts/{attempt}', [QuizController::class, 'attemptShow'])
            ->name('quizzes.attempts.show');
    });

    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/courses', [StudentCourseController::class, 'index'])
            ->name('courses.index');
        Route::post('/courses/enroll', [StudentCourseController::class, 'enroll'])
            ->name('courses.enroll');
        Route::get('/courses/{course}/learn', [StudentCourseController::class, 'learn'])
            ->name('courses.learn');

        Route::get('/my-courses', [StudentCourseController::class, 'index'])
            ->name('my-courses.index');

        Route::get('/quizzes', [StudentQuizController::class, 'index'])
            ->name('quizzes.index');
        Route::get('/quizzes/{quiz}', [StudentQuizController::class, 'show'])
            ->name('quizzes.show');
        Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])
            ->name('quizzes.submit');

        Route::get('/quizzes/{quiz}/submit', function (\App\Models\Quiz $quiz) {
            return redirect()->route('siswa.quizzes.show', $quiz)
                ->with('error', 'Gunakan tombol "Submit" untuk mengirim jawaban.');
        })->name('quizzes.submit.get');

        Route::get('/quiz-attempts/{attempt}', [StudentQuizController::class, 'result'])
            ->name('quiz-attempts.show');

        Route::get('/certificates', [StudentCertificateController::class, 'index'])
            ->name('certificates.index');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])
            ->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('users.store');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])
            ->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])
            ->name('enrollments.index');
        Route::post('/enrollments', [AdminEnrollmentController::class, 'store'])
            ->name('enrollments.store');
        Route::post('/enrollments/assign-tentor', [AdminEnrollmentController::class, 'assignTentor'])
            ->name('enrollments.assign-tentor');
        Route::delete('/enrollments/{id}', [AdminEnrollmentController::class, 'destroy'])
            ->name('enrollments.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/unique-id', [ProfileController::class, 'updateUniqueId'])
        ->name('profile.unique-id')
        ->middleware('role:admin');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
