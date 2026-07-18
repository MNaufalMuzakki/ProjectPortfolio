<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;

new class extends Component
{
    use WithFileUploads;

    public $editMode = false;

    // Profile Data
    public $profile_name;
    public $profile_role;
    public $profile_bio;
    public $profile_github;
    public $profile_linkedin;
    public $profile_email;
    public $profile_whatsapp;
    public $profile_instagram;
    public $profile_address;
    public $profile_avatar;

    // Skill Form
    public $skill_name = '';
    public $skill_category = 'Web Development';
    public $skill_icon = '';

    // Experience Form
    public $exp_role = '';
    public $exp_company = '';
    public $exp_period = '';
    public $exp_description = '';

    // Education Form
    public $edu_type = 'education';
    public $edu_title = '';
    public $edu_subtitle = '';
    public $edu_description = '';
    public $edu_level = 'university';
    public $edu_gpa = '';
    public $edu_eprt = '';
    public $edu_tak = '';
    public $edu_final_grade = '';
    public $edu_certificate_link = '';

    // Project Form
    public $proj_title = '';
    public $proj_category = 'Web Development';
    public $proj_description = '';
    public $proj_url = '';
    public $proj_tech_stack = '';
    public $proj_image;

    public function mount()
    {
        $profile = Profile::first();
        if ($profile) {
            $this->profile_name = $profile->name;
            $this->profile_role = $profile->role;
            $this->profile_bio = $profile->bio;
            $this->profile_github = $profile->github;
            $this->profile_linkedin = $profile->linkedin;
            $this->profile_email = $profile->email;
            $this->profile_whatsapp = $profile->whatsapp;
            $this->profile_instagram = $profile->instagram;
            $this->profile_address = $profile->address;
        }
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    // --- PROFILE CRUD ---
    public function saveProfile()
    {
        $profile = Profile::first();
        if ($profile) {
            $data = [
                'name' => $this->profile_name,
                'role' => $this->profile_role,
                'bio' => $this->profile_bio,
                'github' => $this->profile_github,
                'linkedin' => $this->profile_linkedin,
                'email' => $this->profile_email,
                'whatsapp' => $this->profile_whatsapp,
                'instagram' => $this->profile_instagram,
                'address' => $this->profile_address,
            ];

            if ($this->profile_avatar) {
                if ($profile->avatar_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->avatar_path);
                }
                $path = $this->profile_avatar->store('avatars', 'public');
                $data['avatar_path'] = $path;
            }

            $profile->update($data);
            $this->profile_avatar = null;

            session()->flash('msg_profile', 'Profil berhasil disimpan!');
        }
    }

    // --- SKILLS CRUD ---
    public function addSkill()
    {
        $this->validate([
            'skill_name' => 'required',
            'skill_icon' => 'required',
            'skill_category' => 'required',
        ]);

        Skill::create([
            'name' => $this->skill_name,
            'category' => $this->skill_category,
            'icon' => $this->skill_icon,
        ]);

        $this->reset(['skill_name', 'skill_icon']);
        session()->flash('msg_skill', 'Skill berhasil ditambahkan!');
    }

    public function deleteSkill($id)
    {
        Skill::find($id)?->delete();
    }

    // --- EXPERIENCE CRUD ---
    public function addExperience()
    {
        $this->validate([
            'exp_role' => 'required',
            'exp_company' => 'required',
            'exp_period' => 'required',
        ]);

        Experience::create([
            'role' => $this->exp_role,
            'company' => $this->exp_company,
            'period' => $this->exp_period,
            'description' => $this->exp_description,
        ]);

        $this->reset(['exp_role', 'exp_company', 'exp_period', 'exp_description']);
        session()->flash('msg_exp', 'Pengalaman berhasil ditambahkan!');
    }

    public function deleteExperience($id)
    {
        Experience::find($id)?->delete();
    }

    // --- EDUCATION CRUD ---
    public function addEducation()
    {
        $this->validate([
            'edu_title' => 'required',
            'edu_subtitle' => 'required',
        ]);

        $metrics = null;
        $certLink = null;

        if ($this->edu_type === 'education') {
            if ($this->edu_level === 'university') {
                $metrics = array_filter([
                    'GPA' => $this->edu_gpa,
                    'EPRT' => $this->edu_eprt,
                    'TAK Score' => $this->edu_tak,
                ]);
            } else {
                $metrics = array_filter([
                    'Final Grade' => $this->edu_final_grade,
                ]);
            }
            if (empty($metrics)) {
                $metrics = null;
            }
        } else {
            $certLink = $this->edu_certificate_link ?: null;
        }

        Education::create([
            'type' => $this->edu_type,
            'title' => $this->edu_title,
            'subtitle' => $this->edu_subtitle,
            'description' => $this->edu_description ?: null,
            'metrics' => $metrics,
            'certificate_link' => $certLink,
        ]);

        $this->reset([
            'edu_title', 'edu_subtitle', 'edu_description',
            'edu_gpa', 'edu_eprt', 'edu_tak', 'edu_final_grade',
            'edu_certificate_link'
        ]);
        session()->flash('msg_edu', 'Pendidikan/Sertifikasi berhasil ditambahkan!');
    }

    public function deleteEducation($id)
    {
        Education::find($id)?->delete();
    }

    // --- PROJECTS CRUD ---
    public function addProject()
    {
        $this->validate([
            'proj_title' => 'required',
            'proj_description' => 'required',
            'proj_image' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $imagePath = null;
        if ($this->proj_image) {
            $imagePath = $this->proj_image->store('projects', 'public');
        }

        $techArray = array_filter(array_map('trim', explode(',', $this->proj_tech_stack)));

        Project::create([
            'title' => $this->proj_title,
            'category' => $this->proj_category,
            'description' => $this->proj_description,
            'url' => $this->proj_url ?: null,
            'tech_stack' => array_values($techArray),
            'image_path' => $imagePath,
        ]);

        $this->reset(['proj_title', 'proj_description', 'proj_url', 'proj_tech_stack', 'proj_image']);
        session()->flash('msg_proj', 'Proyek berhasil ditambahkan!');
    }

    public function deleteProject($id)
    {
        Project::find($id)?->delete();
    }

    // --- DATA PROVIDERS ---
    #[Computed]
    public function profile()
    {
        return Profile::first();
    }

    #[Computed]
    public function skills()
    {
        return Skill::all();
    }

    #[Computed]
    public function usedIcons()
    {
        return Skill::select('icon', 'name')
            ->whereNotNull('icon')
            ->where('icon', '!=', '')
            ->get()
            ->unique('icon');
    }

    #[Computed]
    public function educations()
    {
        return Education::all();
    }

    #[Computed]
    public function experiences()
    {
        return Experience::orderBy('created_at', 'desc')->get();
    }

    #[Computed]
    public function projects()
    {
        return Project::all();
    }
};
?>

<div>
    <!-- Background Glow Orbs for Premium Vibe (Hidden in light mode) -->
    <div class="no-print absolute top-10 left-10 w-[300px] h-[300px] bg-brand-500 glow-orb dark:block hidden animate-pulse-slow"></div>
    <div class="no-print absolute top-[40%] right-10 w-[350px] h-[350px] bg-electric-500 glow-orb dark:block hidden animate-pulse-slow"></div>
    <div class="no-print absolute bottom-20 left-[20%] w-[300px] h-[300px] bg-brand-500 glow-orb dark:block hidden animate-pulse-slow"></div>

    <!-- ==========================================
      1. HEADER / NAVIGATION BAR
    ========================================== -->
    <header class="no-print fixed top-0 left-0 w-full z-50 transition-all duration-300" id="mainHeader">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="backdrop-blur-md bg-white/70 dark:bg-slate-950/70 border {{ $editMode ? 'border-amber-500/50 shadow-lg shadow-amber-500/10' : 'border-slate-200 dark:border-slate-800/80 shadow-lg' }} rounded-2xl px-6 py-3 flex items-center justify-between transition-all duration-300">
                <!-- Brand Logo / Monogram -->
                <a href="#hero" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr {{ $editMode ? 'from-amber-500 to-orange-500' : 'from-brand-500 to-electric-500' }} flex items-center justify-center font-extrabold text-white text-lg shadow-lg group-hover:scale-105 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-sm sm:text-base tracking-wide text-slate-800 dark:text-slate-100 group-hover:text-brand-500 transition-colors duration-300">
                        @if($editMode)
                            <span class="text-amber-500">Mode</span> Edit Konten
                        @else
                            <span class="text-brand-500">Muhammad</span> Naufal<span class="text-brand-500"> Muzakki</span>
                        @endif
                    </span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#hero" class="nav-link text-sm font-semibold text-slate-600 dark:text-slate-350 hover:text-brand-500 dark:hover:text-brand-500 transition-colors duration-300">Home</a>
                    <a href="#skills" class="nav-link text-sm font-semibold text-slate-600 dark:text-slate-350 hover:text-brand-500 dark:hover:text-brand-500 transition-colors duration-300">Skills</a>
                    <a href="#experience" class="nav-link text-sm font-semibold text-slate-600 dark:text-slate-350 hover:text-brand-500 dark:hover:text-brand-500 transition-colors duration-300">Experience</a>
                    <a href="#education" class="nav-link text-sm font-semibold text-slate-600 dark:text-slate-350 hover:text-brand-500 dark:hover:text-brand-500 transition-colors duration-300">Education</a>
                    <a href="#portfolio" class="nav-link text-sm font-semibold text-slate-600 dark:text-slate-350 hover:text-brand-500 dark:hover:text-brand-500 transition-colors duration-300">Portfolio</a>
                </nav>

                <!-- Actions: Edit Mode, Theme Toggle & Mobile Menu Btn -->
                <div class="flex items-center gap-3">
                    <!-- Edit Mode Button -->
                    <button wire:click="toggleEditMode" class="px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-2 transition-all duration-300 shadow-md {{ $editMode ? 'bg-amber-500 text-slate-950 hover:bg-amber-600' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-350 hover:text-brand-500 border border-slate-200 dark:border-slate-800' }}">
                        @if($editMode)
                            <i class="fa-solid fa-eye"></i> Lihat Web
                        @else
                            <i class="fa-solid fa-pen-to-square"></i> Edit Konten
                        @endif
                    </button>

                    <!-- Theme Toggle Button -->
                    <button id="themeToggle" class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-400 hover:text-brand-500 hover:border-brand-500/30 active:scale-95 transition-all duration-300" aria-label="Toggle Theme">
                        <i class="fa-solid fa-moon dark:hidden text-lg text-indigo-500"></i>
                        <i class="fa-solid fa-sun hidden dark:block text-lg text-amber-400"></i>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-350 hover:text-brand-500 active:scale-95 transition-all duration-300" aria-label="Open Menu">
                        <i class="fa-solid fa-bars text-base" id="menuIcon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu (Dropdown) -->
        <div class="md:hidden hidden px-4 sm:px-6 mb-4" id="mobileMenu">
            <div class="bg-white/95 dark:bg-slate-950/95 border border-slate-200 dark:border-slate-800/90 rounded-2xl p-4 flex flex-col gap-3 shadow-xl backdrop-blur-lg">
                <a href="#hero" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900 hover:text-brand-500 transition-all duration-300">Home</a>
                <a href="#skills" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900 hover:text-brand-500 transition-all duration-300">Skills</a>
                <a href="#experience" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900 hover:text-brand-500 transition-all duration-300">Experience</a>
                <a href="#education" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900 hover:text-brand-500 transition-all duration-300">Education</a>
                <a href="#portfolio" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900 hover:text-brand-500 transition-all duration-300">Portfolio</a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 relative z-10">

        <!-- ==========================================
          A. HERO SECTION
        ========================================== -->
        <section id="hero" class="py-6 md:py-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center lg:min-h-[75vh]">
            @if($editMode)
                <div class="lg:col-span-12 w-full p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Profil Utama</h2>
                        <div class="flex items-center gap-3">
                            @if (session()->has('msg_profile'))
                                <span class="text-green-400 text-sm font-bold animate-pulse">{{ session('msg_profile') }}</span>
                            @endif
                            <button wire:click="saveProfile" class="bg-amber-500 text-slate-900 px-4 py-2 rounded-xl text-sm font-bold hover:bg-amber-600 transition-colors shadow-md">Simpan Perubahan</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Nama Lengkap</label>
                                <input type="text" wire:model="profile_name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Role / Subtitle</label>
                                <input type="text" wire:model="profile_role" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Bio / Deskripsi Ringkas</label>
                                <textarea wire:model="profile_bio" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white h-[116px] focus:border-amber-500 outline-none transition-all resize-none"></textarea>
                            </div>
                        </div>
                        <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">GitHub URL</label>
                                <input type="text" wire:model="profile_github" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">LinkedIn URL</label>
                                <input type="text" wire:model="profile_linkedin" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Email</label>
                                <input type="email" wire:model="profile_email" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">WhatsApp</label>
                                <input type="text" wire:model="profile_whatsapp" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Instagram</label>
                                <input type="text" wire:model="profile_instagram" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Lokasi / Domisili</label>
                                <input type="text" wire:model="profile_address" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">Foto Profil (Avatar)</label>
                                <div class="flex items-center gap-2">
                                    <input type="file" wire:model="profile_avatar" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition-all">
                                    <div wire:loading wire:target="profile_avatar" class="text-xs text-amber-500 flex-shrink-0">Uploading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Left Branding Info -->
                <div class="lg:col-span-7 flex flex-col items-start text-left order-2 lg:order-1">
                    <!-- Mini Tag -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-500 text-xs font-extrabold tracking-widest uppercase mb-6 shadow-sm shadow-brand-500/5">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                        Telkom University Student
                    </div>

                    <!-- Headline & Titles -->
                    <h1 class="text-3xl sm:text-5xl lg:text-5xl font-black text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 via-teal-400 to-electric-500">{{ $this->profile?->name ?? 'Muhammad Naufal Muzakki' }}</span>
                    </h1>
                    
                    <h2 class="text-sm sm:text-base lg:text-lg font-bold text-slate-700 dark:text-slate-300 mt-4 tracking-wide font-mono flex items-center gap-2">
                        <span class="text-brand-500 font-black">//</span> {{ $this->profile?->role ?? 'Multimedia Engineering Technology' }}
                    </h2>

                    <!-- Main Summary text -->
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-350 mt-6 leading-relaxed max-w-2xl text-left sm:text-justify">
                        {{ $this->profile?->bio ?? 'A Multimedia Engineering student specializing in Web Development, Game Development, and Videography...' }}
                    </p>

                    <!-- Call To Actions -->
                    <div class="flex flex-wrap items-center gap-4 mt-8 w-full sm:w-auto">
                        <a href="#portfolio" class="w-full sm:w-auto px-6 py-4 rounded-xl bg-brand-500 text-slate-950 font-bold text-sm text-center hover:bg-brand-600 hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-brand-500/20 transition-all duration-300">
                            View Portfolio <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                        </a>
                        @if($this->profile?->github)
                            <a href="{{ $this->profile?->github }}" target="_blank" class="w-12 h-12 rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-500 hover:border-brand-500/30 transition-all duration-300 text-lg shadow-sm">
                                <i class="fa-brands fa-github"></i>
                            </a>
                        @endif
                        @if($this->profile?->linkedin)
                            <a href="{{ $this->profile?->linkedin }}" target="_blank" class="w-12 h-12 rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-500 hover:border-brand-500/30 transition-all duration-300 text-lg shadow-sm">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right Avatar & Orbiting Graphic -->
                <div class="lg:col-span-5 flex justify-center items-center relative py-6 order-1 lg:order-2">
                    <!-- Circular Avatar Wrapper with Rotating Neon Rings -->
                    <div class="relative w-[280px] h-[280px] sm:w-[320px] sm:h-[320px] rounded-full p-2 flex items-center justify-center">
                        
                        <!-- Glowing background orbits -->
                        <div class="absolute inset-0 rounded-full border border-dashed border-slate-300 dark:border-slate-800/80 animate-spin-slow"></div>
                        <div class="absolute -inset-4 rounded-full border border-dashed border-brand-500/20 animate-spin-slow" style="animation-direction: reverse; animation-duration: 20s;"></div>
                        
                        <!-- Neon Glow Outer Rings -->
                        <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-brand-500 to-electric-500 blur-sm opacity-60 animate-pulse-slow"></div>
                        
                        <!-- Avatar Image Frame -->
                        <div class="relative w-full h-full rounded-full bg-slate-950 overflow-hidden border-4 border-slate-900 z-10 flex items-center justify-center">
                            
                            <!-- Profile Pic -->
                            @if($this->profile?->avatar_path)
                                <img src="{{ Storage::url($this->profile->avatar_path) }}" alt="{{ $this->profile?->name ?? 'Muhammad Naufal Muzakki' }}" class="w-full h-full object-cover">
                            @else
                                <img src="profile.jpg" alt="Muhammad Naufal Muzakki" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                            @endif
                            
                            <!-- Fallback design -->
                            <div class="hidden w-full h-full bg-gradient-to-br from-slate-900 to-slate-950 flex flex-col items-center justify-center p-6 text-center select-none">
                                <div class="w-16 h-16 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-500 flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-user-astronaut text-3xl animate-float"></i>
                                </div>
                                <span class="font-extrabold text-sm sm:text-base text-slate-100 tracking-wider">MUHAMMAD NAUFAL</span>
                                <span class="text-xs font-mono text-slate-500 uppercase mt-1">Multimedia Engineer</span>
                            </div>
                        </div>

                        <!-- Floating Tech Badges -->
                        <div class="no-print absolute top-0 right-4 w-12 h-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-center text-brand-500 shadow-lg animate-float z-20 hover:scale-110 active:scale-95 transition-all duration-300" style="animation-delay: 0s;" title="Web Developer">
                            <i class="fa-solid fa-code text-base"></i>
                        </div>
                        <div class="no-print absolute bottom-4 left-0 w-12 h-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-center text-electric-500 shadow-lg animate-float z-20 hover:scale-110 active:scale-95 transition-all duration-300" style="animation-delay: 2s;" title="Game Developer">
                            <i class="fa-solid fa-gamepad text-base"></i>
                        </div>
                        <div class="no-print absolute bottom-12 right-2 w-12 h-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-center text-teal-400 shadow-lg animate-float z-20 hover:scale-110 active:scale-95 transition-all duration-300" style="animation-delay: 4s;" title="Videography">
                            <i class="fa-solid fa-clapperboard text-base"></i>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <!-- ==========================================
          B. TECHNICAL SKILL STACK (HARD SKILLS)
        ========================================== -->
        <section id="skills" class="py-10 md:py-12 border-t border-slate-200 dark:border-slate-900">
            <!-- Section Header -->
            <div class="flex flex-col items-start mb-8">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-slate-100 tracking-tight mt-2">Skills & Expertise</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-brand-500 to-electric-500 rounded-full mt-3"></div>
            </div>

            @if($editMode)
                <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Skills</h2>
                            @if (session()->has('msg_skill'))
                                <span class="text-green-400 text-sm font-bold">{{ session('msg_skill') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Form Tambah Skill -->
                    <div class="bg-slate-900/80 p-5 rounded-xl border border-slate-700 mb-6 grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                        <div class="lg:col-span-4 space-y-4">
                            <div>
                                <label class="text-xs text-slate-400 block mb-1">Nama Skill</label>
                                <input type="text" wire:model="skill_name" placeholder="Misal: React" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 block mb-1">Kategori</label>
                                <select wire:model="skill_category" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                    <option value="Web Development">Web Development</option>
                                    <option value="Game Development">Game Development</option>
                                    <option value="Multimedia & Creative">Multimedia & Creative</option>
                                    <option value="Management & Supporting">Management & Supporting</option>
                                </select>
                            </div>
                            <button wire:click="addSkill" class="w-full bg-teal-500 text-slate-900 py-2.5 rounded-lg text-sm font-bold hover:bg-teal-400 transition-colors"><i class="fa-solid fa-plus"></i> Tambah Skill</button>
                        </div>
                        <div class="lg:col-span-8 w-full">
                            <label class="text-xs text-slate-400 block mb-1">Class Icon (FontAwesome)</label>
                            <input type="text" wire:model="skill_icon" placeholder="fa-brands fa-react" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none mb-3">
                            
                            <!-- Quick Select Icons -->
                            @if($this->usedIcons->isNotEmpty())
                            <div class="p-3 bg-slate-950/80 rounded-lg border border-slate-800 space-y-2">
                                <div class="flex flex-wrap justify-between items-center text-[10px] text-slate-400 font-semibold tracking-wider gap-2">
                                    <span>IKON YANG PERNAH DIGUNAKAN</span>
                                    <span>Cari ikon lainnya di: <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-amber-500 hover:text-amber-400 font-bold underline">FontAwesome Free Search <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i></a></span>
                                </div>
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach($this->usedIcons as $used)
                                        <button type="button" wire:click="$set('skill_icon', '{{ $used->icon }}')" class="px-2 py-1 bg-slate-800 hover:bg-amber-500/20 hover:text-amber-400 rounded text-[10px] text-slate-300 border border-slate-700 flex items-center gap-1.5 transition-all animate-fade-in" title="Gunakan {{ $used->icon }}">
                                            <i class="{{ $used->icon }} text-xs"></i>
                                            <span>{{ $used->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="p-3 bg-slate-950/80 rounded-lg border border-slate-800 flex justify-between items-center text-[10px] text-slate-400 font-semibold tracking-wider">
                                <span>Belum ada ikon di database.</span>
                                <span>Cari ikon di: <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-amber-500 hover:text-amber-400 font-bold underline">FontAwesome Free Search <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i></a></span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($this->skills as $skill)
                            <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4 flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <i class="{{ $skill->icon }} text-2xl text-teal-500"></i>
                                    <div>
                                        <h3 class="text-white font-bold text-sm">{{ $skill->name }}</h3>
                                        <span class="text-xs text-slate-400">{{ $skill->category }}</span>
                                    </div>
                                </div>
                                <button wire:click="deleteSkill({{ $skill->id }})" wire:confirm="Yakin ingin menghapus skill ini?" class="text-red-500 hover:text-red-400 transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Skills Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach(['Web Development', 'Game Development', 'Multimedia & Creative', 'Management & Supporting'] as $cat)
                        @php
                            $catSkills = $this->skills->where('category', $cat);
                            if ($cat === 'Multimedia & Creative') {
                                $catSkills = $this->skills->whereIn('category', ['Videography', 'Multimedia & Creative']);
                            }
                        @endphp
                        <div class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 hover:border-brand-500/30 transition-all duration-300 flex flex-col h-full hover:shadow-lg hover:shadow-brand-500/5">
                            <div class="w-11 h-11 rounded-xl bg-brand-500/10 text-brand-500 border border-brand-500/20 flex items-center justify-center mb-5">
                                @if($cat === 'Web Development')
                                    <i class="fa-solid fa-earth-americas text-lg"></i>
                                @elseif($cat === 'Game Development')
                                    <i class="fa-solid fa-gamepad text-lg"></i>
                                @elseif($cat === 'Multimedia & Creative')
                                    <i class="fa-solid fa-photo-film text-lg"></i>
                                @else
                                    <i class="fa-solid fa-screwdriver-wrench text-lg"></i>
                                @endif
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100 uppercase tracking-wide">{{ $cat }}</h3>
                            <p class="text-xs sm:text-sm text-slate-650 dark:text-slate-405 mt-1.5 mb-5">
                                @if($cat === 'Web Development')
                                    Building responsive, secure, and database-driven web applications.
                                @elseif($cat === 'Game Development')
                                    Crafting engaging gameplay mechanics, interactive logic, and visual storytelling.
                                @elseif($cat === 'Multimedia & Creative')
                                    Designing and creating high-quality video production and assets.
                                @else
                                    Coordinating team workflows, project timelines, and utilizing supporting tools.
                                @endif
                            </p>
                            <div class="flex flex-wrap gap-2 mt-auto">
                                @forelse($catSkills as $skill)
                                    <span class="badge-style bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 select-none hover:border-brand-500/30 transition-all duration-300">
                                        <i class="{{ $skill->icon }} text-sm"></i> {{ $skill->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-500 italic">No skills listed yet</span>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- ==========================================
          C. EXPERIENCE
        ========================================== -->
        <section id="experience" class="py-10 md:py-12 border-t border-slate-200 dark:border-slate-900">
            <div class="flex flex-col items-start mb-8">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-slate-100 tracking-tight mt-2">Experience</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-brand-500 to-electric-500 rounded-full mt-3"></div>
            </div>

            @if($editMode)
                <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Experience</h2>
                        @if (session()->has('msg_exp'))
                            <span class="text-green-400 text-sm font-bold">{{ session('msg_exp') }}</span>
                        @endif
                    </div>
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700 mb-4 space-y-3">
                        <input type="text" wire:model="exp_role" placeholder="Peran / Jabatan *" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" wire:model="exp_company" placeholder="Penyelenggara / Organisasi / UKM *" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                            <input type="text" wire:model="exp_period" placeholder="Periode (contoh: Agustus 2023 - Sekarang) *" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                        </div>
                        <textarea wire:model="exp_description" placeholder="Deskripsi peran dan tanggung jawab..." class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none h-20"></textarea>
                        <button wire:click="addExperience" class="w-full bg-teal-500 text-slate-900 py-2 rounded-lg text-sm font-bold hover:bg-teal-400 transition-colors"><i class="fa-solid fa-plus"></i> Tambah</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($this->experiences as $exp)
                            <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-sm">{{ $exp->role }}</h3>
                                    <p class="text-xs text-slate-400">{{ $exp->company }} | {{ $exp->period }}</p>
                                </div>
                                <button wire:click="deleteExperience({{ $exp->id }})" wire:confirm="Yakin hapus experience ini?" class="text-red-500 hover:text-red-400 transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 dark:before:via-slate-700 before:to-transparent">
                    @forelse($this->experiences as $exp)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white dark:border-slate-900 bg-slate-100 dark:bg-slate-800 group-hover:bg-brand-500 group-hover:border-brand-500 text-slate-500 dark:text-slate-400 group-hover:text-slate-950 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-all duration-300 z-10">
                                <i class="fa-solid fa-briefcase text-sm"></i>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 shadow-sm hover:border-brand-500/20 transition-all duration-300">
                                <div class="flex items-center justify-between space-x-2 mb-1.5">
                                    <div class="font-extrabold text-slate-900 dark:text-white text-base">{{ $exp->role }}</div>
                                    <time class="font-mono text-xs font-semibold text-brand-500">{{ $exp->period }}</time>
                                </div>
                                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                                    <i class="fa-solid fa-building text-xs text-brand-500/65"></i> {{ $exp->company }}
                                </div>
                                <div class="text-slate-655 dark:text-slate-355 text-sm leading-relaxed text-justify">
                                    {{ $exp->description }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-500 italic py-4">No experience entries found</div>
                    @endforelse
                </div>
            @endif
        </section>

        <!-- ==========================================
          D. EDUCATION & CERTIFICATIONS
        ========================================== -->
        <section id="education" class="py-10 md:py-12 border-t border-slate-200 dark:border-slate-900">
            <!-- Section Header -->
            <div class="flex flex-col items-start mb-8">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-slate-100 tracking-tight mt-2">Education & Certifications</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-brand-500 to-electric-500 rounded-full mt-3"></div>
            </div>

            @if($editMode)
                <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Education & Certifications</h2>
                        @if (session()->has('msg_edu'))
                            <span class="text-green-400 text-sm font-bold">{{ session('msg_edu') }}</span>
                        @endif
                    </div>
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700 mb-4 space-y-4">
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Tipe Entri</label>
                            <select wire:model.live="edu_type" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                <option value="education">Pendidikan (Education)</option>
                                <option value="certification">Sertifikasi (Certification)</option>
                            </select>
                        </div>

                        @if($edu_type === 'education')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Nama Kampus / Sekolah *</label>
                                    <input type="text" wire:model="edu_title" placeholder="contoh: Telkom University atau SMAN 12 Bandung" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Periode Pendidikan *</label>
                                    <input type="text" wire:model="edu_subtitle" placeholder="contoh: July 2023 - Present atau 2020 - 2023" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Jurusan / Prodi (Opsional)</label>
                                    <input type="text" wire:model="edu_description" placeholder="contoh: Teknologi Rekayasa Multimedia (kosongkan jika tidak ada)" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Tingkat Pendidikan</label>
                                    <select wire:model.live="edu_level" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                        <option value="university">Kuliah (Universitas / Institut / D3 / D4 / S1)</option>
                                        <option value="school">Sekolah (SD / SMP / SMA / SMK)</option>
                                    </select>
                                </div>
                            </div>

                            @if($edu_level === 'university')
                                <div class="p-3 bg-slate-950/50 rounded-lg border border-slate-800/80 space-y-2">
                                    <span class="text-[10px] text-amber-500 font-bold uppercase tracking-wider block">Nilai Kuliah (Kosongkan jika belum ada/ingin disembunyikan):</span>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-[10px] text-slate-400 block mb-1">IPK (GPA)</label>
                                            <input type="text" wire:model="edu_gpa" placeholder="contoh: 3.95" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white text-xs focus:border-amber-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-slate-400 block mb-1">Nilai EPRT</label>
                                            <input type="text" wire:model="edu_eprt" placeholder="contoh: 537" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white text-xs focus:border-amber-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-slate-400 block mb-1">Skor TAK</label>
                                            <input type="text" wire:model="edu_tak" placeholder="contoh: 160" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white text-xs focus:border-amber-500 outline-none">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 bg-slate-950/50 rounded-lg border border-slate-800/80 space-y-2">
                                    <span class="text-[10px] text-amber-500 font-bold uppercase tracking-wider block">Nilai Sekolah (Kosongkan jika belum ada/ingin disembunyikan):</span>
                                    <div class="max-w-xs">
                                        <label class="text-[10px] text-slate-400 block mb-1">Nilai Akhir / Rata-Rata UN / Rapor</label>
                                        <input type="text" wire:model="edu_final_grade" placeholder="contoh: 89.81" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white text-xs focus:border-amber-500 outline-none">
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Nama Sertifikasi *</label>
                                    <input type="text" wire:model="edu_title" placeholder="contoh: BNSP Competency Certificate" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Bagian / Bidang yang Disertifikasi *</label>
                                    <input type="text" wire:model="edu_subtitle" placeholder="contoh: Desainer Multimedia Madya" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 block mb-1">Link Sertifikat (URL Google Drive / Credly dll - Opsional)</label>
                                <input type="text" wire:model="edu_certificate_link" placeholder="contoh: https://drive.google.com/file/d/..." class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 block mb-1">Deskripsi / Detail Sertifikasi (Opsional)</label>
                                <textarea wire:model="edu_description" placeholder="contoh: Diterbitkan oleh BNSP melalui LSP Teknologi Digital. Berlaku 3 tahun..." class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2.5 text-white text-sm focus:border-amber-500 outline-none h-20"></textarea>
                            </div>
                        @endif

                        <button wire:click="addEducation" class="w-full bg-teal-500 text-slate-900 py-2.5 rounded-lg text-sm font-bold hover:bg-teal-400 transition-colors"><i class="fa-solid fa-plus"></i> Tambah Entri</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($this->educations as $edu)
                            <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-sm">{{ $edu->title }}</h3>
                                    <p class="text-xs text-slate-400">{{ $edu->subtitle }} | <span class="capitalize text-amber-500">{{ $edu->type }}</span></p>
                                </div>
                                <button wire:click="deleteEducation({{ $edu->id }})" wire:confirm="Yakin hapus education ini?" class="text-red-500 hover:text-red-400 transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:items-stretch items-start">
                    <!-- Education column (Left) -->
                    <div class="lg:col-span-5 flex flex-col lg:h-full">
                        <h3 class="text-sm font-black uppercase text-slate-550 dark:text-slate-500 tracking-wider mb-4">Education History</h3>
                        
                        <div class="flex-grow flex flex-col gap-6 justify-start">
                            @forelse($this->educations->where('type', 'education') as $edu)
                                @php
                                    $isTelU = str_contains(strtolower($edu->title), 'telkom');
                                @endphp
                                <div class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 hover:border-brand-500/30 transition-all duration-300 flex-1 flex flex-col justify-between hover:shadow-lg hover:shadow-brand-500/5">
                                    <div>
                                        <div class="flex items-center gap-4 mb-3">
                                            <div class="w-10 h-10 rounded-lg {{ $isTelU ? 'bg-red-500/10 text-red-500' : 'bg-electric-500/10 text-electric-500' }} border border-slate-200 dark:border-slate-800 flex items-center justify-center text-xs font-black flex-shrink-0">
                                                {{ $isTelU ? 'Tel-U' : 'HS' }}
                                            </div>
                                            <div>
                                                <h4 class="text-base font-extrabold text-slate-900 dark:text-slate-100">{{ $edu->title }}</h4>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $edu->subtitle }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $edu->description }}</p>
                                    </div>
                                    
                                    @if($edu->metrics)
                                        @php
                                            $metricCount = count($edu->metrics);
                                        @endphp
                                        <div class="grid {{ $metricCount === 1 ? 'grid-cols-1 max-w-[150px] mx-auto' : 'grid-cols-3' }} gap-2 mt-4 pt-4 border-t border-slate-200 dark:border-slate-850 text-center">
                                            @foreach($edu->metrics as $key => $value)
                                                @php
                                                    $metricUrls = [
                                                        'GPA' => 'https://drive.google.com/file/d/1jlXrOmKrob637b5eHfDO_yVWFnq9zrrZ/view?usp=drive_link',
                                                        'EPRT' => 'https://drive.google.com/file/d/1elpXLMgUG90eMd9A5nBRZ_5Tby4pN1NQ/view?usp=drive_link',
                                                        'TAK Score' => 'https://drive.google.com/file/d/152FsaXSpO6aZ-yax1m6-eiDCMFAdLd6o/view?usp=drive_link'
                                                    ];
                                                    $url = $metricUrls[$key] ?? null;
                                                @endphp
                                                @if($url)
                                                    <a href="{{ $url }}" target="_blank" class="bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg border border-slate-200 dark:border-slate-850 hover:border-brand-500/40 hover:shadow-md hover:shadow-brand-500/5 active:scale-95 transition-all duration-300 group outline-none flex flex-col items-center justify-center">
                                                        <span class="block text-sm sm:text-base font-black text-brand-600 dark:text-brand-400 group-hover:text-brand-500 transition-colors">{{ $value }}</span>
                                                        <span class="text-[10px] font-mono text-slate-550 dark:text-slate-550 uppercase tracking-wider block mt-0.5">{{ $key }}</span>
                                                    </a>
                                                @else
                                                    <div class="bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg border border-slate-200 dark:border-slate-850 flex flex-col items-center justify-center">
                                                        <span class="block text-sm sm:text-base font-black text-brand-600 dark:text-brand-400">{{ $value }}</span>
                                                        <span class="text-[10px] font-mono text-slate-550 dark:text-slate-550 uppercase tracking-wider block mt-0.5">{{ $key }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-slate-500 italic">No education entries found</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Certification column (Right) -->
                    <div class="lg:col-span-7 flex flex-col lg:h-full">
                        <h3 class="text-sm font-black uppercase text-slate-550 dark:text-slate-500 tracking-wider mb-4">Professional Credentials</h3>
                        
                        <div class="space-y-6">
                            @forelse($this->educations->where('type', 'certification') as $cert)
                                <div class="card-style bg-white dark:bg-slate-900/10 backdrop-blur-md border border-slate-200 dark:border-brand-500/20 rounded-2xl p-6 relative overflow-hidden transition-all duration-300 hover:border-brand-500/40 hover:shadow-lg hover:shadow-brand-500/5 flex-grow flex flex-col justify-between">
                                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-brand-500 blur-2xl opacity-20 pointer-events-none"></div>
                                    
                                    <div class="relative z-10 flex-grow flex flex-col">
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 justify-between border-b border-slate-200 dark:border-slate-800/80 pb-5 mb-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-tr from-amber-400 to-yellow-600 flex items-center justify-center text-slate-950 shadow-md flex-shrink-0 animate-float select-none">
                                                    <i class="fa-solid fa-award text-2xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-xs font-mono font-bold tracking-wider text-amber-600 dark:text-amber-500 uppercase">OFFICIAL CERTIFICATION</h4>
                                                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-slate-100 mt-0.5">{{ $cert->title }}</h3>
                                                </div>
                                            </div>
                                            @if($cert->certificate_link)
                                                <div class="no-print font-sans">
                                                    <a href="{{ $cert->certificate_link }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 hover:border-brand-500/30 hover:text-brand-500 transition-all duration-300 active:scale-95 flex items-center gap-1.5">
                                                        <i class="fa-regular fa-image text-brand-500"></i> View Certificate
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <h4 class="text-lg font-black text-slate-900 dark:text-slate-100 leading-snug">
                                            {{ $cert->subtitle }}
                                        </h4>
                                        <p class="text-base text-slate-600 dark:text-slate-300 mt-4 leading-relaxed text-justify">
                                            {{ $cert->description }}
                                        </p>
                                    </div>
                                    
                                    <div class="mt-5 flex flex-wrap gap-2 relative z-10">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-brand-500/20 transition-all duration-300 select-none">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span> Creative Research & Design
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-brand-500/20 transition-all duration-300 select-none">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span> Audio-Visual Production
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-brand-500/20 transition-all duration-300 select-none">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span> Interactive Programming
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-slate-550 dark:text-slate-500 italic">No certifications found</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <!-- ==========================================
          E. PROJECTS PORTFOLIO
        ========================================== -->
        <section id="portfolio" class="py-10 md:py-12 border-t border-slate-200 dark:border-slate-900">
            <!-- Section Header -->
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Projects Portfolio</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-brand-500 to-electric-500 rounded-full mt-3"></div>
            </div>

            @if($editMode)
                <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Projects</h2>
                        @if (session()->has('msg_proj'))
                            <span class="text-green-400 text-sm font-bold">{{ session('msg_proj') }}</span>
                        @endif
                    </div>

                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700 mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <input type="text" wire:model="proj_title" placeholder="Judul Project *" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                            
                            <select wire:model="proj_category" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                                <option value="Web Development">Web Development</option>
                                <option value="Game Development">Game Development</option>
                                <option value="Videography">Videography</option>
                                <option value="Other">Other</option>
                            </select>
                            
                            <input type="text" wire:model="proj_url" placeholder="Link URL (opsional)" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                            
                            <input type="text" wire:model="proj_tech_stack" placeholder="Tech Stack (pisah dengan koma: Laravel, PHP)" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                        </div>
                        <div class="flex flex-col gap-3">
                            <textarea wire:model="proj_description" placeholder="Deskripsi project *" class="flex-1 w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none"></textarea>
                            
                            <div class="flex items-center gap-3">
                                <input type="file" wire:model="proj_image" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-white hover:file:bg-slate-700">
                                <div wire:loading wire:target="proj_image" class="text-xs text-amber-500">Uploading...</div>
                            </div>
                            
                            <button wire:click="addProject" class="w-full bg-teal-500 text-slate-900 py-2.5 rounded-lg text-sm font-bold hover:bg-teal-400 transition-colors"><i class="fa-solid fa-plus"></i> Tambah Project</button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($this->projects as $project)
                            <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    @if($project->image_path)
                                        <img src="{{ Storage::url($project->image_path) }}" class="w-12 h-12 object-cover rounded-lg border border-slate-700">
                                    @endif
                                    <div>
                                        <h3 class="text-white font-bold">{{ $project->title }}</h3>
                                        <p class="text-sm text-slate-400">{{ $project->category }}</p>
                                    </div>
                                </div>
                                <button wire:click="deleteProject({{ $project->id }})" wire:confirm="Yakin hapus project ini?" class="text-red-500 hover:text-red-400 transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Projects Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="projectsGrid">
                    @forelse($this->projects as $project)
                        @php
                            $isWeb = $project->category === 'Web Development';
                            $isGame = $project->category === 'Game Development';
                        @endphp
                        <div class="card-style bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/85 rounded-2xl overflow-hidden hover:border-brand-500/30 transition-all duration-300 flex flex-col group hover:shadow-lg">
                            <!-- Project Thumbnail Wrapper -->
                            <div class="relative h-48 bg-slate-950 overflow-hidden flex items-center justify-center">
                                @if($project->image_path)
                                    <img src="{{ Storage::url($project->image_path) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <!-- Elegant geometric fallback design -->
                                    <div class="w-full h-full bg-gradient-to-br {{ $isWeb ? 'from-brand-500/10' : ($isGame ? 'from-electric-500/10' : 'from-brand-500/10') }} to-slate-900 dark:to-slate-950 flex flex-col items-center justify-center p-6 text-center select-none">
                                        @if($isWeb)
                                            <i class="fa-solid fa-window-restore text-4xl text-brand-500/40 mb-2 animate-float"></i>
                                        @elseif($isGame)
                                            <i class="fa-solid fa-gamepad text-4xl text-electric-500/40 mb-2 animate-float"></i>
                                        @else
                                            <i class="fa-solid fa-clapperboard text-4xl text-brand-500/40 mb-2 animate-float"></i>
                                        @endif
                                        <span class="font-bold text-slate-300 dark:text-slate-350 text-xs sm:text-sm tracking-wider font-mono uppercase">{{ $project->category }}</span>
                                    </div>
                                @endif
                                
                                <!-- Project Tag badge overlay -->
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded bg-slate-950/80 border border-slate-850/80 text-xs font-mono font-bold tracking-wider {{ $isWeb ? 'text-brand-400' : ($isGame ? 'text-electric-500' : 'text-brand-400') }} uppercase select-none">
                                    {{ $project->category }}
                                </span>
                            </div>

                            <!-- Project Info -->
                            <div class="p-5 flex flex-col flex-grow">
                                <h4 class="text-base font-extrabold text-slate-900 dark:text-slate-100 mb-2">{{ $project->title }}</h4>
                                <p class="text-sm text-slate-650 dark:text-slate-350 leading-relaxed text-justify mb-4">
                                    {{ $project->description }}
                                </p>
                                
                                <!-- Tech Tags -->
                                @if($project->tech_stack)
                                    <div class="flex flex-wrap gap-1.5 mt-auto mb-5">
                                        @foreach($project->tech_stack as $tech)
                                            <span class="badge-style bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 px-2.5 py-1 rounded text-xs font-mono text-slate-600 dark:text-slate-400">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Link Button -->
                                @if($project->url)
                                    <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-850/60 no-print">
                                        <a href="{{ $project->url }}" target="_blank" class="flex items-center justify-center gap-2 w-full px-3 py-2.5 rounded-lg bg-brand-500 text-slate-950 font-extrabold text-xs text-center uppercase tracking-wider hover:bg-brand-600 active:scale-95 transition-all duration-300">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i> Buka Link
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-slate-500 italic py-4">No projects listed yet</div>
                    @endforelse
                </div>
            @endif
        </section>

        <!-- ==========================================
          F. CONTACT SECTION (FOOTER)
        ========================================== -->
        <section id="contact" class="py-10 md:py-12 border-t border-slate-200 dark:border-slate-900">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left Side Info -->
                <div class="lg:col-span-5 flex flex-col items-start">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Get In Touch</h2>
                    <div class="w-16 h-1 bg-gradient-to-r from-brand-500 to-electric-500 rounded-full mt-3 mb-6"></div>
                    
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed text-justify mb-6 max-w-sm">
                        Thank you for visiting! I am always open to discussing new multimedia project collaborations, interactive system developments, audio-visual production, or internship/contract opportunities. Please reach out to me through any of the contacts listed on the right.
                    </p>
                    
                    <p class="text-xs sm:text-sm text-slate-500 italic max-w-sm">
                        "Creating immersive digital experiences through design, development, and audio-visual precision."
                    </p>
                </div>

                <!-- Right Side Contact Cards Grid -->
                <div class="lg:col-span-7 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Email Card -->
                        <div class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0 flex-grow">
                                <div class="w-11 h-11 rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-500 dark:text-brand-400 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-envelope text-base"></i>
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <span class="block text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email</span>
                                    <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 truncate" title="{{ $this->profile?->email ?? 'mnaufalmuza@student.telkomuniversity.ac.id' }}">{{ $this->profile?->email ?? 'mnaufalmuza@student.telkomuniversity.ac.id' }}</span>
                                </div>
                            </div>
                            <!-- Copy Email Action -->
                            <button id="copyEmailBtn" data-email="{{ $this->profile?->email ?? 'mnaufalmuza@student.telkomuniversity.ac.id' }}" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 flex items-center justify-center text-slate-400 hover:text-brand-500 hover:border-brand-500/30 active:scale-90 transition-all duration-200 flex-shrink-0 z-10 no-print" title="Copy Email">
                                <i class="fa-regular fa-copy text-xs" id="copyEmailIcon"></i>
                            </button>
                        </div>

                        <!-- WhatsApp Card -->
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $this->profile?->whatsapp ?? '628112120582') }}" target="_blank" class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 hover:border-emerald-500/40 hover:shadow-md hover:shadow-emerald-500/5 active:scale-[0.98] transition-all duration-300 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-phone text-base"></i>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <span class="block text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">WhatsApp</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 truncate">Chat on WhatsApp</span>
                            </div>
                        </a>

                        <!-- Instagram Card -->
                        <a href="https://www.instagram.com/{{ ltrim($this->profile?->instagram ?? 'naufal_mauzakki', '@') }}" target="_blank" class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 hover:border-pink-500/40 hover:shadow-md hover:shadow-pink-500/5 active:scale-[0.98] transition-all duration-300 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-500 dark:text-pink-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-brands fa-instagram text-base"></i>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <span class="block text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Instagram</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ $this->profile?->instagram ?? '@naufal_mauzakki' }}</span>
                            </div>
                        </a>

                        <!-- LinkedIn Card -->
                        <a href="{{ $this->profile?->linkedin ?? 'https://www.linkedin.com/in/muhammad-naufal-muzakki-962674292/' }}" target="_blank" class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 hover:border-electric-500/40 hover:shadow-md hover:shadow-electric-500/5 active:scale-[0.98] transition-all duration-300 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-electric-500/10 border border-electric-500/20 text-electric-650 dark:text-electric-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-brands fa-linkedin-in text-base"></i>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <span class="block text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">LinkedIn</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ $this->profile?->name ?? 'Muhammad Naufal Muzakki' }}</span>
                            </div>
                        </a>

                        <!-- GitHub Card -->
                        <a href="{{ $this->profile?->github ?? 'https://github.com/MNaufalMuzakki' }}" target="_blank" class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 hover:border-slate-500/40 hover:shadow-md hover:shadow-slate-500/5 active:scale-[0.98] transition-all duration-300 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center flex-shrink-0">
                                <i class="fa-brands fa-github text-base"></i>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <span class="block text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">GitHub</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ basename($this->profile?->github ?? 'MNaufalMuzakki') }}</span>
                            </div>
                        </a>

                        <!-- Domisili Card -->
                        <div class="card-style bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-500 dark:text-brand-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-location-dot text-base"></i>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <span class="block text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Location</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 leading-snug">{{ $this->profile?->address ?? 'Derwati, Rancasari, Bandung' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Simplified footer copyright -->
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-900 text-center text-xs font-mono text-slate-500">
                <p>&copy; 2026 Muhammad Naufal Muzakki. All Rights Reserved.</p>
            </div>
        </section>
    </main>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="no-print fixed bottom-6 right-6 z-40 w-11 h-11 rounded-xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-brand-500 hover:border-brand-500/30 active:scale-95 shadow-lg opacity-0 pointer-events-none translate-y-4 transition-all duration-300 flex items-center justify-center" aria-label="Scroll to Top">
        <i class="fa-solid fa-arrow-up text-base"></i>
    </button>
</div>
