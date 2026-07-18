<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;

new class extends Component
{
    public $editMode = false;

    // Profile Data
    public $profile_name;
    public $profile_role;
    public $profile_bio;
    public $profile_github;
    public $profile_linkedin;

    // Skill Form
    public $skill_name = '';
    public $skill_category = 'Web Development';
    public $skill_icon = '';

    public function mount()
    {
        $profile = Profile::first();
        if ($profile) {
            $this->profile_name = $profile->name;
            $this->profile_role = $profile->role;
            $this->profile_bio = $profile->bio;
            $this->profile_github = $profile->github;
            $this->profile_linkedin = $profile->linkedin;
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
            $profile->update([
                'name' => $this->profile_name,
                'role' => $this->profile_role,
                'bio' => $this->profile_bio,
                'github' => $this->profile_github,
                'linkedin' => $this->profile_linkedin,
            ]);
            session()->flash('msg_profile', 'Profile berhasil disimpan!');
        }
    }

    // --- SKILLS CRUD ---
    public function addSkill()
    {
        $this->validate([
            'skill_name' => 'required',
            'skill_icon' => 'required'
        ]);

        Skill::create([
            'name' => $this->skill_name,
            'category' => $this->skill_category,
            'icon' => $this->skill_icon,
        ]);

        $this->reset(['skill_name', 'skill_icon']);
        session()->flash('msg_skill', 'Skill ditambahkan!');
    }

    public function deleteSkill($id)
    {
        Skill::find($id)?->delete();
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
    <div class="absolute top-10 left-10 w-[300px] h-[300px] bg-teal-500 glow-orb hidden dark:block animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-[400px] h-[400px] bg-blue-600 glow-orb hidden dark:block animate-pulse"></div>

    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-300 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="backdrop-blur-md bg-white/70 dark:bg-slate-950/70 border {{ $editMode ? 'border-amber-500/50 shadow-amber-500/20' : 'border-slate-200 dark:border-slate-800/80' }} rounded-2xl px-6 py-3 flex items-center justify-between shadow-lg transition-colors duration-500">
                
                <a href="#hero" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr {{ $editMode ? 'from-amber-500 to-orange-500' : 'from-teal-500 to-blue-500' }} flex items-center justify-center font-extrabold text-white text-lg transition-colors shadow-lg">
                        <i class="fa-solid {{ $editMode ? 'fa-pen-ruler' : 'fa-code' }}"></i>
                    </div>
                    <span class="font-extrabold text-sm sm:text-base tracking-wide text-slate-800 dark:text-slate-100">
                        @if($editMode)
                            <span class="text-amber-500">Mode</span> Edit
                        @else
                            <span class="text-teal-500">Naufal</span> Muzakki
                        @endif
                    </span>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    <a href="#hero" class="text-sm font-semibold hover:text-teal-500 transition-colors">Home</a>
                    <a href="#skills" class="text-sm font-semibold hover:text-teal-500 transition-colors">Skills</a>
                    <a href="#experience" class="text-sm font-semibold hover:text-teal-500 transition-colors">Experience & Education</a>
                    <a href="#projects" class="text-sm font-semibold hover:text-teal-500 transition-colors">Projects</a>
                </nav>

                <div class="flex items-center gap-3">
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16 relative z-10">
        
        <!-- HERO SECTION -->
        <section id="hero" class="min-h-[80vh] flex flex-col justify-center items-center text-center relative mt-10">
            @if($editMode)
                <div class="w-full max-w-3xl p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Edit Profile</h2>
                        <div class="flex items-center gap-3">
                            @if (session()->has('msg_profile'))
                                <span class="text-green-400 text-sm font-bold animate-pulse">{{ session('msg_profile') }}</span>
                            @endif
                            <button wire:click="saveProfile" class="bg-amber-500 text-slate-900 px-4 py-2 rounded-xl text-sm font-bold hover:bg-amber-600 transition-colors">Simpan</button>
                        </div>
                    </div>
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="text-sm font-semibold text-slate-400 mb-1 block">Nama Lengkap</label>
                            <input type="text" wire:model="profile_name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-400 mb-1 block">Role / Jabatan</label>
                            <input type="text" wire:model="profile_role" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-400 mb-1 block">Bio Singkat</label>
                            <textarea wire:model="profile_bio" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white h-32 focus:border-amber-500 outline-none transition-all"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">GitHub URL</label>
                                <input type="text" wire:model="profile_github" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-400 mb-1 block">LinkedIn URL</label>
                                <input type="text" wire:model="profile_linkedin" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="animate-float">
                    <div class="inline-block px-4 py-1.5 rounded-full border border-teal-500/30 bg-teal-500/10 text-teal-400 text-sm font-semibold mb-6">
                        👋 Halo, perkenalkan saya
                    </div>
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-4 text-slate-800 dark:text-white">
                        {{ $this->profile->name }}
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-blue-500 mb-6">
                        {{ $this->profile->role }}
                    </h2>
                    <p class="max-w-2xl mx-auto text-lg text-slate-600 dark:text-slate-400 mb-10 leading-relaxed">
                        {{ $this->profile->bio }}
                    </p>
                    
                    <div class="flex items-center justify-center gap-4">
                        <a href="#projects" class="px-8 py-3 rounded-xl bg-teal-500 text-slate-900 font-bold hover:bg-teal-400 transition-all shadow-lg shadow-teal-500/30">
                            Lihat Karya Saya
                        </a>
                        @if($this->profile->github)
                        <a href="{{ $this->profile->github }}" target="_blank" class="w-12 h-12 rounded-xl flex items-center justify-center border border-slate-700 hover:border-teal-500 hover:text-teal-500 transition-all text-xl">
                            <i class="fa-brands fa-github"></i>
                        </a>
                        @endif
                        @if($this->profile->linkedin)
                        <a href="{{ $this->profile->linkedin }}" target="_blank" class="w-12 h-12 rounded-xl flex items-center justify-center border border-slate-700 hover:border-teal-500 hover:text-teal-500 transition-all text-xl">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        @endif
                    </div>
                </div>
            @endif
        </section>

        <!-- SKILLS SECTION -->
        <section id="skills" class="py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 dark:text-white mb-4">Tech Stack & Skills</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto rounded-full"></div>
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
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Nama Skill</label>
                            <input type="text" wire:model="skill_name" placeholder="Misal: React" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Class Icon (FontAwesome)</label>
                            <input type="text" wire:model="skill_icon" placeholder="fa-brands fa-react" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Kategori</label>
                            <select wire:model="skill_category" class="w-full bg-slate-800 border border-slate-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                                <option value="Web Development">Web Development</option>
                                <option value="Game Development">Game Development</option>
                                <option value="Videography">Videography</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <button wire:click="addSkill" class="w-full bg-teal-500 text-slate-900 px-4 py-2 rounded-lg text-sm font-bold hover:bg-teal-400 transition-colors"><i class="fa-solid fa-plus"></i> Tambah</button>
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
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($this->skills as $skill)
                        <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col items-center justify-center gap-4 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-500/10 transition-all group">
                            <i class="{{ $skill->icon }} text-5xl text-slate-400 group-hover:text-teal-500 transition-colors"></i>
                            <h3 class="text-slate-800 dark:text-white font-bold">{{ $skill->name }}</h3>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- EXPERIENCE & EDUCATION SECTION -->
        <section id="experience" class="py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 dark:text-white mb-4">Experience & Education</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto rounded-full"></div>
            </div>

            @if($editMode)
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Edit Experience -->
                    <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Experience</h2>
                            <button class="bg-amber-500 text-slate-900 px-3 py-1.5 rounded-xl text-sm font-bold hover:bg-amber-600"><i class="fa-solid fa-plus"></i> Tambah</button>
                        </div>
                        <div class="space-y-4">
                            @foreach($this->experiences as $exp)
                                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex justify-between mb-2">
                                        <h3 class="text-white font-bold">{{ $exp->role }}</h3>
                                        <button class="text-red-500"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                    <p class="text-sm text-slate-400">{{ $exp->company }} | {{ $exp->period }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Edit Education -->
                    <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Education</h2>
                            <button class="bg-amber-500 text-slate-900 px-3 py-1.5 rounded-xl text-sm font-bold hover:bg-amber-600"><i class="fa-solid fa-plus"></i> Tambah</button>
                        </div>
                        <div class="space-y-4">
                            @foreach($this->educations as $edu)
                                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex justify-between mb-2">
                                        <h3 class="text-white font-bold">{{ $edu->title }}</h3>
                                        <button class="text-red-500"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                    <p class="text-sm text-slate-400">{{ $edu->subtitle }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Experience -->
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-3">
                            <i class="fa-solid fa-briefcase text-teal-500"></i> Work Experience
                        </h3>
                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 dark:before:via-slate-700 before:to-transparent">
                            @foreach($this->experiences as $exp)
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white dark:border-slate-900 bg-slate-100 dark:bg-slate-800 group-hover:bg-teal-500 group-hover:border-teal-500 text-slate-500 dark:text-slate-400 group-hover:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-colors z-10">
                                        <i class="fa-solid fa-check text-sm"></i>
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm">
                                        <div class="flex items-center justify-between space-x-2 mb-1">
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $exp->role }}</div>
                                            <time class="font-caveat font-medium text-teal-500">{{ $exp->period }}</time>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-500 mb-2">{{ $exp->company }}</div>
                                        <div class="text-slate-600 dark:text-slate-400 text-sm">{{ $exp->description }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Education -->
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-3">
                            <i class="fa-solid fa-graduation-cap text-teal-500"></i> Education
                        </h3>
                        <div class="space-y-6">
                            @foreach($this->educations as $edu)
                                <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 hover:border-teal-500/50 transition-colors">
                                    <h4 class="text-xl font-bold text-slate-800 dark:text-white">{{ $edu->title }}</h4>
                                    <p class="text-teal-500 font-semibold text-sm mb-3">{{ $edu->subtitle }}</p>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">{{ $edu->description }}</p>
                                    @if($edu->metrics)
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($edu->metrics as $key => $value)
                                                <div class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                    {{ $key }}: <span class="text-teal-500">{{ $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <!-- PROJECTS SECTION -->
        <section id="projects" class="py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 dark:text-white mb-4">Featured Projects</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto rounded-full"></div>
            </div>

            @if($editMode)
                <div class="p-6 rounded-2xl border-2 border-dashed border-amber-500/50 bg-amber-500/5 backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-amber-500 font-bold text-xl"><i class="fa-solid fa-pen"></i> Kelola Projects</h2>
                        <button class="bg-amber-500 text-slate-900 px-4 py-2 rounded-xl text-sm font-bold hover:bg-amber-600 transition-colors"><i class="fa-solid fa-plus"></i> Tambah Project</button>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($this->projects as $project)
                            <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-white font-bold">{{ $project->title }}</h3>
                                    <p class="text-sm text-slate-400">{{ $project->category }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="text-blue-500 hover:text-blue-400 px-3 py-1"><i class="fa-solid fa-edit"></i></button>
                                    <button class="text-red-500 hover:text-red-400 px-3 py-1"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($this->projects as $project)
                        <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-teal-500/10 transition-all group">
                            <div class="h-48 bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                @if($project->image_path)
                                    <img src="{{ Storage::url($project->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <i class="fa-solid fa-image text-4xl text-slate-600 group-hover:scale-110 transition-transform duration-500"></i>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-60"></div>
                            </div>
                            <div class="p-6">
                                <div class="text-teal-500 text-sm font-bold mb-2">{{ $project->category }}</div>
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-3">{{ $project->title }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-3">
                                    {{ $project->description }}
                                </p>
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @if($project->tech_stack)
                                        @foreach($project->tech_stack as $tech)
                                            <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">{{ $tech }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                @if($project->url)
                                <a href="{{ $project->url }}" target="_blank" class="text-sm font-bold text-slate-800 dark:text-white hover:text-teal-500 transition-colors">
                                    Lihat Detail <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </main>
</div>
