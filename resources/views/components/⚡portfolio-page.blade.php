<?php

use Livewire\Component;

new class extends Component
{
    public $editMode = false; // Default: Mode View

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }
};
?>

<div>
    <!-- Background Glow Orbs -->
    <div class="absolute top-10 left-10 w-[300px] h-[300px] bg-teal-500 glow-orb hidden dark:block"></div>

    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-300 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="backdrop-blur-md bg-white/70 dark:bg-slate-950/70 border {{ $editMode ? 'border-amber-500/50 shadow-amber-500/20' : 'border-slate-200 dark:border-slate-800/80' }} rounded-2xl px-6 py-3 flex items-center justify-between shadow-lg transition-colors duration-500">
                
                <!-- Logo -->
                <a href="#hero" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr {{ $editMode ? 'from-amber-500 to-orange-500' : 'from-teal-500 to-blue-500' }} flex items-center justify-center font-extrabold text-white text-lg transition-colors shadow-lg">
                        <i class="fa-solid {{ $editMode ? 'fa-pen-ruler' : 'fa-code' }}"></i>
                    </div>
                    <span class="font-extrabold text-sm sm:text-base tracking-wide text-slate-800 dark:text-slate-100">
                        @if($editMode)
                            <span class="text-amber-500">Mode</span> Edit Aktif
                        @else
                            <span class="text-teal-500">Muhammad</span> Naufal
                        @endif
                    </span>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#hero" class="text-sm font-semibold hover:text-teal-500 transition-colors">Home</a>
                    <a href="#skills" class="text-sm font-semibold hover:text-teal-500 transition-colors">Skills</a>
                    <a href="#portfolio" class="text-sm font-semibold hover:text-teal-500 transition-colors">Portfolio</a>
                </nav>

                <!-- Tombol Toggle Edit -->
                <div class="flex items-center gap-3">
                    
                    <!-- TOMBOL SAKTI: TOGGLE MODE VIEW/EDIT TANPA RELOAD -->
                    <button wire:click="toggleEditMode" class="px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-2 transition-all duration-300 shadow-md {{ $editMode ? 'bg-amber-500 text-slate-950 hover:bg-amber-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-teal-500 border border-slate-700' }}">
                        @if($editMode)
                            <i class="fa-solid fa-eye"></i> Lihat Web
                        @else
                            <i class="fa-solid fa-pen-to-square"></i> Edit Konten
                        @endif
                    </button>

                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16 relative z-10 text-center">
        <!-- Sementara teks dummy untuk melihat efeknya -->
        @if($editMode)
            <div class="mt-20 p-8 rounded-2xl border border-amber-500/30 bg-amber-500/5">
                <h1 class="text-3xl text-amber-500 font-bold"><i class="fa-solid fa-screwdriver-wrench animate-bounce"></i> Kamu sedang dalam Mode Edit!</h1>
                <p class="mt-4 text-slate-400">Nanti form tambah/ubah/hapus data (CRUD) akan muncul di sini.</p>
            </div>
        @else
            <div class="mt-20 p-8 rounded-2xl border border-teal-500/30 bg-teal-500/5">
                <h1 class="text-3xl text-teal-500 font-bold"><i class="fa-solid fa-globe animate-pulse"></i> Tampilan Publik (View Mode)</h1>
                <p class="mt-4 text-slate-400">Ini adalah tampilan yang akan dilihat oleh pengunjung web portofoliomu. Rapi dan tidak ada tombol edit.</p>
            </div>
        @endif
    </main>
</div>
