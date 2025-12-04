<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        
        if ($type && in_array($type, ['income', 'expenditure'])) {
            $categories = Category::where('type', $type)->get();
        } else {
            $categories = Category::all();
        }
        
        return view('category', compact('categories', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'type' => 'required|in:income,expenditure',
        ]);

        $data = $request->only('category_name', 'type');
        $data['slug'] = Str::slug($request->input('category_name'));

        Category::create($data);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function getByType($type)
    {
        $categories = Category::where('type', $type)->get();
        return response()->json($categories);
    }
}
