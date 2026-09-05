<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_returns_local_file_inline(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');
        Storage::disk('public')->put('documents/sample.pdf', 'PDF-CONTENT');

        $doc = Document::create([
            'user_id' => $user->id,
            'name' => 'sample.pdf',
            'file_path' => 'documents/sample.pdf',
            'file_name' => 'sample.pdf',
            'file_size' => 11,
            'file_type' => 'pdf',
        ]);

        $response = $this->actingAs($user)->get("/documents/{$doc->id}/preview");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertSame('PDF-CONTENT', $response->streamedContent());
    }

    public function test_download_returns_local_file(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');
        Storage::disk('public')->put('documents/sample.pdf', 'PDF-CONTENT');

        $doc = Document::create([
            'user_id' => $user->id,
            'name' => 'sample.pdf',
            'file_path' => 'documents/sample.pdf',
            'file_name' => 'sample.pdf',
            'file_size' => 11,
            'file_type' => 'pdf',
        ]);

        $response = $this->actingAs($user)->get("/documents/{$doc->id}/download");

        $response->assertOk();
        $this->assertSame('PDF-CONTENT', $response->streamedContent());
    }
}