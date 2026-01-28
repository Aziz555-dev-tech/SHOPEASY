<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SousCategory;
use App\Models\SubType;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder {
    public function run() {
        $data = [
            'mode' => [
                "Vêtements"   => ["Hommes","Femmes","Enfants"],
                "Chaussures"  => ["Hommes","Femmes","Sport"],
                "Accessoires" => ["Sacs","Bijoux","Montres"],
            ],
            'hightech' => [
                "Téléphones"   => ["Android","iPhone","Accessoires"],
                "Ordinateurs"  => ["Portable","Bureau","Accessoires"],
                "Audio / Vidéo"=> ["Écouteurs","Enceintes","Télévisions"],
            ],
            'maison' => [
                "Cuisine"     => ["Ustensiles","Électroménager","Vaisselle"],
                "Décoration"  => ["Maison","Bureau","Luminaires"],
                "Meubles"     => ["Salon","Chambre","Bureau"],
            ],
            'sport' => [
                "Sport"   => ["Fitness","Football","Accessoires"],
                "Loisirs" => ["Jeux","Musique","Plein air"],
            ],
        ];

        foreach ($data as $slug => $sous) {
            $cat = Category::create([
                'name' => ucfirst($slug),
                'slug' => $slug
            ]);

            foreach ($sous as $sousName => $subTypes) {
                $s = SousCategory::create([
                    'category_id' => $cat->id,
                    'name' => $sousName,
                    'slug' => Str::slug($sousName)
                ]);

                foreach ($subTypes as $t) {
                    SubType::create([
                        'sous_category_id' => $s->id,
                        'name' => $t,
                        'slug' => Str::slug($t)
                    ]);
                }
            }
        }
    }
}
