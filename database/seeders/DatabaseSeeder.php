<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@samadocs.com'],
            [
                'name' => 'Mouhamed Diallo',
                'first_name' => 'Mouhamed',
                'last_name' => 'Diallo',
                'password' => Hash::make('password'),
            ]
        );

        $categories = [
            ['name' => 'Administratif', 'color' => '#3b82f6', 'icon' => 'fa-folder'],
            ['name' => 'Éducation', 'color' => '#8b5cf6', 'icon' => 'fa-graduation-cap'],
            ['name' => 'Travail', 'color' => '#06b6d4', 'icon' => 'fa-briefcase'],
            ['name' => 'Personnel', 'color' => '#22c55e', 'icon' => 'fa-user'],
            ['name' => 'Finance', 'color' => '#f59e0b', 'icon' => 'fa-coins'],
            ['name' => 'Autres', 'color' => '#9ca3af', 'icon' => 'fa-ellipsis-h'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['name']] = Category::firstOrCreate(
                ['user_id' => $user->id, 'name' => $cat['name']],
                $cat
            );
        }

        $documents = [
            ['name' => 'Certificat de scolarité.pdf', 'cat' => 'Éducation', 'size' => 1258291, 'type' => 'pdf', 'fav' => true, 'desc' => 'Certificat de scolarité de l\'année 2025-2026.'],
            ['name' => 'Contrat de travail.pdf', 'cat' => 'Travail', 'size' => 870400, 'type' => 'pdf', 'fav' => false, 'desc' => 'Contrat de travail signé avec l\'entreprise.'],
            ['name' => 'CNI.jpg', 'cat' => 'Personnel', 'size' => 2202009, 'type' => 'jpg', 'fav' => true, 'desc' => 'Copie de la carte nationale d\'identité.'],
            ['name' => 'Relevé de notes.pdf', 'cat' => 'Éducation', 'size' => 1153433, 'type' => 'pdf', 'fav' => false, 'desc' => 'Relevé de notes du semestre 1.'],
            ['name' => 'CV.pdf', 'cat' => 'Personnel', 'size' => 972800, 'type' => 'pdf', 'fav' => true, 'desc' => 'Curriculum vitae à jour.'],
            ['name' => 'Facture_082026.pdf', 'cat' => 'Finance', 'size' => 250880, 'type' => 'pdf', 'fav' => false, 'desc' => 'Facture mensuelle de téléphone.'],
            ['name' => 'Attestation_pole_emploi.pdf', 'cat' => 'Administratif', 'size' => 327680, 'type' => 'pdf', 'fav' => false, 'desc' => 'Attestation France Travail.'],
            ['name' => 'Photo_identite.jpg', 'cat' => 'Personnel', 'size' => 1887436, 'type' => 'jpg', 'fav' => false, 'desc' => 'Photo d\'identité récente.'],
            ['name' => 'Planning_septembre.xlsx', 'cat' => 'Travail', 'size' => 159744, 'type' => 'xlsx', 'fav' => false, 'desc' => 'Planning de travail du mois de septembre.'],
        ];

        foreach ($documents as $doc) {
            // Create a real placeholder file so download works
            $path = 'documents/' . str_replace(' ', '_', $doc['name']);
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, "SamaDocs - Document de démonstration : " . $doc['name'] . "\n");
            }

            Document::firstOrCreate(
                ['user_id' => $user->id, 'name' => $doc['name']],
                [
                    'category_id' => $categoryModels[$doc['cat']]->id,
                    'description' => $doc['desc'],
                    'file_path' => $path,
                    'file_name' => $doc['name'],
                    'file_size' => $doc['size'],
                    'file_type' => $doc['type'],
                    'is_favorite' => $doc['fav'],
                ]
            );
        }
    }
}
