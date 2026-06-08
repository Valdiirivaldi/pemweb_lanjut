<div class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i data-lucide="menu" style="width:22px;height:22px;"></i>
        </button>
        <div class="topbar-breadcrumb">
            @hasSection('breadcrumb')
                @yield('breadcrumb')
            @else
                <a href="{{ route('home') }}">Home</a>
                <i data-lucide="chevron-right"></i>
                <span class="current">@yield('page-title', 'Dashboard')</span>
            @endif
        </div>
    </div>

    <div class="topbar-right">
        <span class="live-clock" id="liveClock" style="font-size:0.78rem;color:var(--text-subtle);font-weight:500;letter-spacing:0.3px;white-space:nowrap;"></span>

        <button class="topbar-btn" id="searchToggle" type="button" title="Search (Ctrl+K)" onclick="openSearchModal()">
            <i data-lucide="search" style="width:18px;height:18px;"></i>
        </button>

        <button class="topbar-btn" type="button" title="Notifications">
            <i data-lucide="bell" style="width:18px;height:18px;"></i>
            <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
        </button>

        <button class="topbar-btn theme-toggle" id="themeToggle" type="button" title="Toggle theme">
            <i data-lucide="moon" style="width:18px;height:18px;" id="themeIcon"></i>
        </button>

        <div class="dropdown topbar-user-dropdown">
            <button class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="topbar-avatar-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span>{{ Auth::user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 14px; border: none; padding: 8px;">
                <li>
                    <a class="dropdown-item py-2 rounded-2" href="{{ route('profile') }}">
                        <i data-lucide="user" style="width:16px;height:16px;margin-right:10px;color:#4e73df;"></i>Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 rounded-2 text-danger">
                            <i data-lucide="log-out" style="width:16px;height:16px;margin-right:10px;"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Search Modal --}}
<div class="modal fade search-modal" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="search-modal-search">
                    <i data-lucide="search"></i>
                    <input type="text" id="searchInput" placeholder="Search pages..." autofocus
                           onkeyup="filterSearchResults(this.value)">
                </div>
                <div class="search-modal-results" id="searchResults">
                    @php
                        $user = Auth::user();
                        $routes = [];
                        switch($user->role) {
                            case 'admin':
                                $routes = [
                                    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'layout-dashboard'],
                                    ['label' => 'Manage Users', 'url' => route('admin.users.index'), 'icon' => 'users'],
                                    ['label' => 'Enrollments', 'url' => route('admin.enrollments.index'), 'icon' => 'user-check'],
                                    ['label' => 'Add Account', 'url' => route('admin.users.create'), 'icon' => 'user-plus'],
                                    ['label' => 'Profile', 'url' => route('profile'), 'icon' => 'user'],
                                ];
                                break;
                            case 'tentor':
                                $routes = [
                                    ['label' => 'Dashboard', 'url' => route('tentor.dashboard'), 'icon' => 'layout-dashboard'],
                                    ['label' => 'My Courses', 'url' => route('tentor.courses.index'), 'icon' => 'book-open'],
                                    ['label' => 'Modules', 'url' => route('tentor.modules.index'), 'icon' => 'layers'],
                                    ['label' => 'Quizzes', 'url' => route('tentor.quizzes.index'), 'icon' => 'help-circle'],
                                    ['label' => 'Participants', 'url' => route('tentor.students.index'), 'icon' => 'users'],
                                    ['label' => 'Profile', 'url' => route('profile'), 'icon' => 'user'],
                                ];
                                break;
                            case 'siswa':
                                $routes = [
                                    ['label' => 'Dashboard', 'url' => route('siswa.dashboard'), 'icon' => 'layout-dashboard'],
                                    ['label' => 'My Courses', 'url' => route('siswa.courses.index'), 'icon' => 'book-open'],
                                    ['label' => 'Quiz History', 'url' => route('siswa.quizzes.index'), 'icon' => 'clock'],
                                    ['label' => 'Certificates', 'url' => route('siswa.certificates.index'), 'icon' => 'award'],
                                    ['label' => 'Profile', 'url' => route('profile'), 'icon' => 'user'],
                                ];
                                break;
                        }
                    @endphp
                    @foreach($routes as $r)
                        <a href="{{ $r['url'] }}" class="search-modal-item" data-search="{{ strtolower($r['label']) }}">
                            <i data-lucide="{{ $r['icon'] }}"></i>
                            <span>{{ $r['label'] }}</span>
                            <span class="kbd">↵</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openSearchModal() {
    var modal = new bootstrap.Modal(document.getElementById('searchModal'));
    modal.show();
    setTimeout(function() {
        document.getElementById('searchInput')?.focus();
    }, 300);
}

function filterSearchResults(value) {
    var q = value.toLowerCase().trim();
    document.querySelectorAll('.search-modal-item').forEach(function(item) {
        var text = item.getAttribute('data-search') || item.textContent.toLowerCase();
        item.style.display = (!q || text.includes(q)) ? 'flex' : 'none';
    });
}

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        openSearchModal();
    }
});
</script>
