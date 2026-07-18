<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Naufal Muzakki</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        /* Animasi Glow & Custom Scrollbar dari desainmu */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .glow-orb { position: absolute; border-radius: 50%; filter: blur(140px); opacity: 0.12; z-index: 0; pointer-events: none; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #090d16; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #14b8a6; }
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'Fira Code', monospace; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen transition-colors duration-300 relative overflow-x-hidden">
    
    <!-- Panggil komponen Livewire Halaman Portofolio -->
    <livewire:portfolio-page />

    <!-- Link JS logic -->
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
            });
        }

        // Mobile Menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Copy Email
        const copyEmailBtn = document.getElementById('copyEmailBtn');
        const copyEmailIcon = document.getElementById('copyEmailIcon');
        if (copyEmailBtn && copyEmailIcon) {
            copyEmailBtn.addEventListener('click', (e) => {
                e.preventDefault();
                navigator.clipboard.writeText('mnaufalmuza@student.telkomuniversity.ac.id');
                copyEmailIcon.className = 'fa-solid fa-check text-emerald-500';
                setTimeout(() => {
                    copyEmailIcon.className = 'fa-regular fa-copy text-xs';
                }, 2000);
            });
        }

        // Scroll to Top
        const scrollToTop = document.getElementById('scrollToTop');
        if (scrollToTop) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    scrollToTop.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                } else {
                    scrollToTop.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                }
            });
            scrollToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    </script>
    @livewireScripts
</body>
</html>
