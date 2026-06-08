<aside class="sidebar" id="sidebar">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <i data-lucide="graduation-cap" style="width:28px;height:28px;color:#4e73df;"></i>
        Eduria
    </a>

    <nav class="sidebar-nav">
        <div class="sidebar-section">Menu</div>

        @switch(auth()->user()->role)
            @case('tentor')
                <a href="{{ route('tentor.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('tentor.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>Dashboard
                </a>
                <a href="{{ route('tentor.courses.index') }}"
                   class="sidebar-link {{ request()->routeIs('tentor.courses.*') ? 'active' : '' }}">
                    <i data-lucide="book-open"></i>My Courses
                </a>
                <a href="{{ route('tentor.modules.index') }}"
                   class="sidebar-link {{ request()->routeIs('tentor.modules.*') ? 'active' : '' }}">
                    <i data-lucide="layers"></i>Modules
                </a>
                <a href="{{ route('tentor.quizzes.index') }}"
                   class="sidebar-link {{ request()->routeIs('tentor.quizzes.*') ? 'active' : '' }}">
                    <i data-lucide="help-circle"></i>Quizzes
                </a>
                <a href="{{ route('tentor.students.index') }}"
                   class="sidebar-link {{ request()->routeIs('tentor.students.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>Participants
                </a>
                @break

            @case('admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>Manage Users
                </a>
                <a href="{{ route('admin.enrollments.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}">
                    <i data-lucide="user-check"></i>Enrollments
                </a>
                @break

            @case('siswa')
                <a href="{{ route('siswa.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>Dashboard
                </a>
                <a href="{{ route('siswa.courses.index') }}"
                   class="sidebar-link {{ request()->routeIs('siswa.courses.*') ? 'active' : '' }}">
                    <i data-lucide="book-open"></i>My Courses
                </a>
                <a href="{{ route('siswa.quizzes.index') }}"
                   class="sidebar-link {{ request()->routeIs('siswa.quizzes.*') ? 'active' : '' }}">
                    <i data-lucide="clock"></i>Quiz History
                </a>
                <a href="{{ route('siswa.certificates.index') }}"
                   class="sidebar-link {{ request()->routeIs('siswa.certificates.*') ? 'active' : '' }}">
                    <i data-lucide="award"></i>Certificates
                </a>
                @break

            @default
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>Dashboard
                </a>
        @endswitch
    </nav>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-user-role">{{ Auth::user()->role }}</div>
        </div>
        <a href="{{ route('logout') }}"
           class="sidebar-user-logout"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           title="Logout">
            <i data-lucide="log-out" style="width:18px;height:18px;"></i>
        </a>
    </div>
</aside>
