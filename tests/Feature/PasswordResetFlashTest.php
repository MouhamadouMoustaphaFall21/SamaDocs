<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordResetFlashTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_link_flash_shows_in_french()
    {
        Mail::fake();

        User::create([
            'name' => 'Demo User',
            'first_name' => 'Demo',
            'last_name' => 'User',
            'email' => 'demo@samadocs.com',
            'password' => bcrypt('password'),
        ]);

        $this->from('/forgot-password')
            ->post('/forgot-password', ['email' => 'demo@samadocs.com'])
            ->assertSessionHas('status')
            ->assertRedirect('/forgot-password');

        $response = $this->get('/forgot-password');
        $response->assertSee('Nous vous avons envoyé un lien de réinitialisation par e-mail');
    }
}
