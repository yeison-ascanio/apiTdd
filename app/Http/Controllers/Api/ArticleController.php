<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Article $article) : ArticleResource
    {
        return ArticleResource::make($article);
    }

    public function index() : ArticleCollection
    {
        return ArticleCollection::make(Article::all());
    }

    public function create(Request $request){

        $article = Article::create([
            'title'   => $request->input('data.attributes.title'),
            'slug'    => $request->input('data.attributes.slug'),
            'content' => $request->input('data.attributes.content')
        ]);

        return ArticleResource::make($article);
    }
}
