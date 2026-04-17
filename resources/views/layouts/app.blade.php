<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    @include('layouts.navigation')

    <div class="max-w-4xl mx-auto px-4 py-8">

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </div>

    @stack('scripts')
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const badges = document.querySelectorAll('[data-countdown]');

        function update() {
            const now = new Date();
            now.setHours(0, 0, 0, 0);

            badges.forEach(badge => {
                const completed = badge.dataset.completed === 'true';
                const due = new Date(badge.dataset.countdown);
                due.setHours(0, 0, 0, 0);

                const diffMs = due - now;
                const diffDays = Math.round(diffMs / (1000 * 60 * 60 * 24));

                if (completed) {
                    badge.textContent = '✅ Concluída';
                    badge.style.backgroundColor = '#22c55e';
                    return;
                }

                if (diffDays < 0) {
                    badge.textContent = `🔴 Atrasada ${Math.abs(diffDays)} dia(s)`;
                    badge.style.backgroundColor = '#ef4444';
                } else if (diffDays === 0) {
                    badge.textContent = '🟠 Deadline hoje!';
                    badge.style.backgroundColor = '#f97316';
                } else if (diffDays <= 3) {
                    badge.textContent = `🟡 ${diffDays} dia(s) restante(s)`;
                    badge.style.backgroundColor = '#eab308';
                } else {
                    badge.textContent = `⏰ ${diffDays} dia(s) restante(s)`;
                    badge.style.backgroundColor = '#3b82f6';
                }
            });
        }

        update();
        setInterval(update, 60000); // atualiza a cada minuto
    });
</script>
