@extends('layouts.dashboard')

@section('title', 'Dashboard - Eduria')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        .empty-state-img {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 16px;
            line-height: 1;
        }
    </style>
@endpush

@section('content')
    {{-- Welcome --}}

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4"
                style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; border-radius: 14px; padding: 24px;">
                <div>
                    <i data-lucide="graduation-cap" style="width:40px;height:40px;opacity:0.3;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #fff;" id="greeting">Selamat, {{ $user->name }}!</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;" id="greetingMsg">
                        Selamat datang di Dashboard Eduria.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bb-alert');
            alerts.forEach(function(el) {
                setTimeout(function() {
                    try {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                        bsAlert.close();
                    } catch (e) {
                        el.style.display = 'none';
                    }
                }, 5000);
            });

            var hour = new Date().getHours();
            var greetingEl = document.getElementById('greeting');

            if (greetingEl) {
                var name = @json($user->name);
                var greet = 'Selamat ';
                if (hour >= 3 && hour < 11) greet += 'Pagi';
                else if (hour >= 11 && hour < 15) greet += 'Siang';
                else if (hour >= 15 && hour < 18) greet += 'Sore';
                else greet += 'Malam';
                greetingEl.textContent = greet + ', ' + name + '!';
            }
        });
    </script>
@endpush
