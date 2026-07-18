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
            'role' => 'Multimedia Engineering Technology',
            'bio' => 'A Multimedia Engineering student specializing in Web Development, Game Development, and Videography. These technical skills are enhanced by strong soft skills in teamwork, project coordination, and timeline management, proven across class projects, competitions, and leadership roles in campus organizations. This experience enables me to deliver end-to-end projects efficiently, utilizing AI tools to streamline execution. With a solid balance of system logic, user experience focus, and clear team communication, I am equipped to excel in collaborative Full-stack or Multimedia Engineer Developer roles.',
            'email' => 'mnaufalmuza@student.telkomuniversity.ac.id',
            'whatsapp' => '+62 811 212 0582',
            'instagram' => '@naufal_mauzakki',
            'linkedin' => 'https://www.linkedin.com/in/muhammad-naufal-muzakki-962674292/',
            'github' => 'https://github.com/MNaufalMuzakki',
            'address' => 'Derwati, Rancasari, Bandung',
        ]);

        $skills = [
            ['name' => 'PHP', 'icon' => 'fa-brands fa-php', 'category' => 'Web Development'],
            ['name' => 'Laravel', 'icon' => 'fa-brands fa-laravel', 'category' => 'Web Development'],
            ['name' => 'HTML', 'icon' => 'fa-brands fa-html5', 'category' => 'Web Development'],
            ['name' => 'CSS', 'icon' => 'fa-brands fa-css3-alt', 'category' => 'Web Development'],
            ['name' => 'JS', 'icon' => 'fa-brands fa-square-js', 'category' => 'Web Development'],
            ['name' => 'MySQL', 'icon' => 'fa-solid fa-database', 'category' => 'Web Development'],
            ['name' => 'PostgreSQL', 'icon' => 'fa-solid fa-database', 'category' => 'Web Development'],

            ['name' => 'Unity / C#', 'icon' => 'fa-brands fa-unity', 'category' => 'Game Development'],
            ['name' => 'Ren\'Py', 'icon' => 'fa-solid fa-book-open', 'category' => 'Game Development'],

            ['name' => 'Premiere Pro', 'icon' => 'fa-solid fa-file-video', 'category' => 'Multimedia & Creative'],
            ['name' => 'After Effects', 'icon' => 'fa-solid fa-wand-magic-sparkles', 'category' => 'Multimedia & Creative'],
            ['name' => 'CapCut', 'icon' => 'fa-solid fa-video', 'category' => 'Multimedia & Creative'],
            ['name' => 'Figma', 'icon' => 'fa-brands fa-figma', 'category' => 'Multimedia & Creative'],
            ['name' => 'Canva', 'icon' => 'fa-solid fa-palette', 'category' => 'Multimedia & Creative'],

            ['name' => 'MS Excel', 'icon' => 'fa-regular fa-file-excel', 'category' => 'Management & Supporting'],
            ['name' => 'MS Word', 'icon' => 'fa-regular fa-file-word', 'category' => 'Management & Supporting'],
            ['name' => 'AI Tools', 'icon' => 'fa-solid fa-robot', 'category' => 'Management & Supporting'],
            ['name' => 'Testing', 'icon' => 'fa-solid fa-bug', 'category' => 'Management & Supporting'],
            ['name' => 'Teamwork & Leadership', 'icon' => 'fa-solid fa-users', 'category' => 'Management & Supporting'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        Education::create([
            'type' => 'education',
            'title' => 'Telkom University',
            'subtitle' => 'July 2023 - Present',
            'description' => 'Applied Bachelor\'s Degree (D4) - Multimedia Engineering Technology',
            'metrics' => [
                'GPA' => '3.95',
                'EPRT' => '537',
                'TAK Score' => '160'
            ],
        ]);

        Education::create([
            'type' => 'education',
            'title' => 'SMA Negeri 12 Bandung',
            'subtitle' => 'July 2020 - May 2023',
            'description' => 'High School - MIPA Science Major',
            'metrics' => [
                'Final Grade' => '89.81'
            ],
        ]);

        Education::create([
            'type' => 'certification',
            'title' => 'BNSP Competency Certificate',
            'subtitle' => 'Desainer Multimedia Madya',
            'description' => 'Issued by Badan Nasional Sertifikasi Profesi (BNSP) through LSP Teknologi Digital. Competency certificate valid from July 2025 until July 2028.',
            'certificate_link' => 'https://drive.google.com/file/d/1wSKmVVNs0y6e0vapsYyttoCClpVxN-01/view?usp=drive_link',
        ]);

        Experience::create([
            'role' => 'Multimedia & Creative Staff',
            'company' => 'UKM Multimedia Telkom University',
            'period' => 'Agustus 2023 - Sekarang',
            'description' => 'Bertanggung jawab dalam memproduksi aset media visual, mengedit video dokumentasi kegiatan, serta merancang desain publikasi digital untuk meningkatkan engagement media sosial organisasi.',
        ]);

        Experience::create([
            'role' => 'Koordinator Publikasi & Dokumentasi',
            'company' => 'Himpunan Mahasiswa Rekayasa Multimedia (HIMA TRM)',
            'period' => 'Agustus 2024 - Sekarang',
            'description' => 'Mengoordinasikan tim dokumentasi untuk meliput seluruh rangkaian acara himpunan, mengelola konten publikasi media sosial, serta memastikan publikasi digital berjalan sesuai timeline.',
        ]);

        Project::create([
            'title' => 'Web Development Portfolio',
            'category' => 'Web Development',
            'description' => 'Web development specializing in organizational information systems and event vendor/tenant platforms.',
            'url' => 'https://drive.google.com/drive/folders/1f1TCtUFikpiEFJ3B2NqxTnPj_docObRf?usp=drive_link',
            'tech_stack' => ['Laravel', 'PHP', 'MySQL', 'Tailwind', 'JS', 'HTML', 'CSS'],
        ]);

        Project::create([
            'title' => 'Game Development Portfolio',
            'category' => 'Game Development',
            'description' => '2D/3D game development, such as 2D game on pixel-art Unity games and simple interactive visual novels.',
            'url' => 'https://drive.google.com/drive/folders/18faco7pVJv7QMStSyTnA73n43OGkwmMJ?usp=drive_link',
            'tech_stack' => ['Unity / C#', 'Ren\'Py'],
        ]);

        Project::create([
            'title' => 'Videography Portfolio',
            'category' => 'Videography',
            'description' => 'Videography services including cinematography, video editing, simple motion graphics, and narrative storytelling.',
            'url' => 'https://drive.google.com/drive/folders/1imDToAlfzeOn2xN6Z7WcODRT0S54oJ3F?usp=drive_link',
            'tech_stack' => ['Premiere Pro', 'After Effects', 'CapCut', 'Canva'],
        ]);
    }
}
