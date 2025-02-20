<?php

namespace App\Http\Controllers;

use App\Models\book;
use Illuminate\Http\Request;

/**
 *
 */
class bookController extends Controller
{
    public function createbook(Request $request){
        return view('back.create');
    }


    public function storebook(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('books_image', 'public');
            } else {
                return back()->withErrors(['image' => 'Le fichier image est invalide.']);
            }

            Book::create([
                'title' => $validate['title'],
                'author' => $validate['author'],
                'description' => $validate['description'],
                'image' => $imagePath,
                'price' => $validate['price'] ?? null,
            ]);

            return redirect()->route('home')->with('success', 'Livre ajouté avec succès !');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur s\'est produ : ' . $e->getMessage()]);
        }
    }


    public function deletebook($id){

        $book = book::find($id);
        $book->delete();


        return back()->with('success', 'Post deleted successfully');;

    }
    public function updateBook($id){
        $book = book::find($id);

        return view('back.update')->with('book', $book);
    }
    public function storeUpdate(Request $request, $id){

        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0'
        ]);


        $book = book::find($id);
        $book->update($validate);
        return redirect()->route('home')->with('success', 'Livre ajouté avec succès !');


    }




}
