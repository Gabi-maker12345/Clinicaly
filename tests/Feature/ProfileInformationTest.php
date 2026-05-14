<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_profile_information_is_available(): void
    {
        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->email, $component->state['email']);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $this->actingAs($user = User::factory()->create());

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
            ->call('updateProfileInformation');

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }

    public function test_profile_photo_can_be_updated_without_retyping_profile_fields(): void
    {
        Storage::fake('public');

        $this->actingAs($user = User::factory()->create());
        $originalName = $user->name;
        $originalEmail = $user->email;
        $photo = UploadedFile::fake()->createWithContent(
            'avatar.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
        );

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => '', 'email' => ''])
            ->set('photo', $photo)
            ->call('updateProfileInformation')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
        $this->assertEquals($originalName, $user->name);
        $this->assertEquals($originalEmail, $user->email);
    }

    public function test_blank_profile_fields_keep_current_values(): void
    {
        $this->actingAs($user = User::factory()->create());
        $originalName = $user->name;
        $originalEmail = $user->email;

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => '', 'email' => ''])
            ->call('updateProfileInformation')
            ->assertHasNoErrors();

        $this->assertEquals($originalName, $user->fresh()->name);
        $this->assertEquals($originalEmail, $user->fresh()->email);
    }

    public function test_profile_photo_url_uses_public_storage_path(): void
    {
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/avatar.jpg',
        ]);

        $this->assertEquals('/storage/profile-photos/avatar.jpg', $user->profile_photo_url);
    }

    public function test_profile_photo_endpoint_updates_photo(): void
    {
        Storage::fake('public');

        $this->actingAs($user = User::factory()->create());
        $photo = UploadedFile::fake()->createWithContent(
            'avatar.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
        );

        $response = $this->postJson(route('profile.photo.update'), [
            'photo' => $photo,
        ]);

        $response->assertOk()
            ->assertJsonPath('url', fn ($url) => str_starts_with($url, '/storage/profile-photos/'));

        $user->refresh();

        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
    }
}
