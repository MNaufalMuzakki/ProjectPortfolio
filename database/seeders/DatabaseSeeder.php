<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Profile::create([
            'name' => 'Muhammad Naufal Muzakki',
            'role' => 'Multimedia Engineering Student',
            'bio' => 'Multimedia Engineering Technology student at Telkom University. Specialized in Web Development, Game Development, and Videography.',
            'email' => 'naufal@example.com',
            'github' => 'https://github.com/naufal',
            'linkedin' => 'https://linkedin.com/in/naufal',
        ]);

        $skills = [
            ['name' => 'Laravel', 'icon' => 'fa-brands fa-laravel', 'category' => 'Web Development'],
            ['name' => 'Tailwind CSS', 'icon' => 'fa-brands fa-css3-alt', 'category' => 'Web Development'],
            ['name' => 'Unity', 'icon' => 'fa-brands fa-unity', 'category' => 'Game Development'],
            ['name' => 'Premiere Pro', 'icon' => 'fa-solid fa-video', 'category' => 'Videography'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        Education::create([
            'type' => 'education',
            'title' => 'Telkom University',
            'subtitle' => 'July 2023 - Present',
            'description' => "Applied Bachelor's Degree (D4) - Multimedia Engineering Technology",
            'metrics' => [
                'GPA' => '3.95',
                'EPRT' => '537',
                'TAK Score' => '160'
            ],
        ]);

        Experience::create([
            'role' => 'Web Developer',
            'company' => 'Freelance',
            'period' => 'Jan 2024 - Present',
            'description' => 'Mengembangkan berbagai aplikasi web menggunakan Laravel dan React.',
        ]);

        Project::create([
            'title' => 'Portfolio Interaktif',
            'category' => 'Web Development',
            'description' => 'Website portofolio single-page dengan fitur Edit Mode real-time.',
            'tech_stack' => ['Laravel 13', 'Livewire', 'Tailwind CSS', 'SQLite'],
        ]);
    }
}
