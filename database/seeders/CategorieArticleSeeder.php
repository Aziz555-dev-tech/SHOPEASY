<?php

namespace Database\Seeders;

use App\Models\CategorieArticle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategorieArticle::updateOrCreate(
            ['nom' => 'article-communique',
             'slug' => 'Article Communiqué',
            ]
        );

        CategorieArticle::updateOrCreate(
            ['nom' => 'article-Promotion',
            'slug' => 'Article Promotion',
            ],
        );
    }
}
