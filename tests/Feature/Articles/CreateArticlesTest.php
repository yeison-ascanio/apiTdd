<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'new article',
                    'slug' => 'new-article',
                    'content' => 'article content'
                ]
            ]
        ]);

        $response->assertCreated();

        $article = Article::first();

        $response->withHeaders([
            'Location',
            route('api.v1.articles.show', $article)
        ]); 

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'new article',
                    'slug' => 'new-article',
                    'content' => 'article content'
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }
}
