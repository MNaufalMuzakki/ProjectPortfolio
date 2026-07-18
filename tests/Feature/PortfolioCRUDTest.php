<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Project;

class PortfolioCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat data profil default untuk pengujian
        Profile::create([
            'name' => 'Muhammad Naufal Muzakki',
            'role' => 'Multimedia Engineering Student',
            'bio' => 'Multimedia Engineering Technology student at Telkom University.',
            'email' => 'naufal@example.com',
            'github' => 'https://github.com/naufal',
            'linkedin' => 'https://linkedin.com/in/naufal',
        ]);
    }

    public function test_can_render_portfolio_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeLivewire('portfolio-page');
    }

    public function test_can_toggle_edit_mode(): void
    {
        Livewire::test('portfolio-page')
            ->assertSet('editMode', false)
            ->call('toggleEditMode')
            ->assertSet('editMode', true)
            ->call('toggleEditMode')
            ->assertSet('editMode', false);
    }

    public function test_can_save_profile(): void
    {
        Livewire::test('portfolio-page')
            ->set('profile_name', 'Naufal Baru')
            ->set('profile_role', 'Fullstack Developer')
            ->set('profile_bio', 'Bio baru yang keren.')
            ->call('saveProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('profiles', [
            'name' => 'Naufal Baru',
            'role' => 'Fullstack Developer',
        ]);
    }

    public function test_can_add_and_delete_skill(): void
    {
        Livewire::test('portfolio-page')
            ->set('skill_name', 'Vue.js')
            ->set('skill_icon', 'fa-brands fa-vuejs')
            ->set('skill_category', 'Web Development')
            ->call('addSkill')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('skills', [
            'name' => 'Vue.js',
            'category' => 'Web Development',
        ]);

        $skill = Skill::where('name', 'Vue.js')->first();

        Livewire::test('portfolio-page')
            ->call('deleteSkill', $skill->id);

        $this->assertSoftDeleted($skill);
    }

    public function test_can_add_project(): void
    {
        Livewire::test('portfolio-page')
            ->set('proj_title', 'Sistem Pendaftaran')
            ->set('proj_category', 'Web Development')
            ->set('proj_description', 'Sistem pendaftaran berbasis web.')
            ->set('proj_selected_skills', ['Laravel', 'Livewire', 'Tailwind'])
            ->call('addProject')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => 'Sistem Pendaftaran',
            'category' => 'Web Development',
        ]);
    }

    public function test_can_save_profile_with_avatar(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $file = \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg');

        Livewire::test('portfolio-page')
            ->set('profile_name', 'Naufal Baru')
            ->set('profile_role', 'Fullstack Developer')
            ->set('profile_bio', 'Bio baru yang keren.')
            ->set('profile_avatar', $file)
            ->call('saveProfile')
            ->assertHasNoErrors();

        $profile = Profile::first();
        $this->assertNotNull($profile->avatar_path);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($profile->avatar_path);
    }
}
