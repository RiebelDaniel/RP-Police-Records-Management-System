<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function index()
    {
        $news  = News::orderBy('id', 'desc')->take(3)->get();
        return view('News.list',[
            'newses' => $news
        ]);
    }


    public function create()
    {
        return view('News.create');
    }


    public function store()
    {
        $validated = request()->validate([
            'text' => ['required','min:3'],
        ]);


        $validated['creator_id'] = auth()->user()->id;

        News::create($validated);
        return redirect('/');

    }


    public function show(News $news)
    {
        return view('News.show',[
            'news' => $news
        ]);
    }


    public function edit(News $news)
    {
        //
    }


    public function update( News $news)
    {
        $validated = request()->validate([
            'text' => ['min:3']
        ]);

        $news->text = $validated['text'];

        $news->save();

        return redirect('/news');

    }


    public function destroy(News $news)
    {
        $news->delete();
        return redirect('/');
    }
}
