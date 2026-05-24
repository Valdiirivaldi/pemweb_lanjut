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
        Route::get('/modules', [ModuleController::class, 'index'])
            ->name('modules.index');
        Route::get('/quizzes', [QuizController::class, 'index'])
            ->name('quizzes.index');
        Route::get('/quizzes/create', [QuizController::class, 'create'])
            ->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'store'])
            ->name('quizzes.store');
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
    });

    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        Route::get('/my-courses', [\App\Http\Controllers\Siswa\CourseController::class, 'myCourses'])
            ->name('my-courses.index');

        Route::get('/courses/{course}/learn', [\App\Http\Controllers\Siswa\CourseController::class, 'learn'])
            ->name('courses.learn')
            ->middleware('role:siswa');

        Route::get('/quizzes/{quiz}', [\App\Http\Controllers\Siswa\QuizController::class, 'show'])
            ->name('quizzes.show');

        Route::post('/quizzes/{quiz}/submit', [\App\Http\Controllers\Siswa\QuizController::class, 'submit'])
            ->name('quizzes.submit');

        Route::get('/quiz-attempts/{attempt}', [\App\Http\Controllers\Siswa\QuizController::class, 'result'])
            ->name('quiz-attempts.show');
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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
