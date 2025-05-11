<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('category.create')->with('success', 'Category added successfully!');
    }

    // Show all categories
    public function index() {
        $categories = Category::all();
        return view('admin.categories.manage', compact('categories'));
    }

    // Edit category
    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('category.manage')->with('success', 'Category updated successfully!');
    }

    // Delete category
    public function destroy($id) {
        $category = Category::findOrFail($id);
     
        // Check if the category is linked to requirements
        if ($category->requests()->count() > 0) {
            return redirect()->route('category.manage')
                ->with('error', 'Cannot delete this category because it is currently in use.');
        }

        $category->delete(); 
        return redirect()->route('category.manage')->with('success', 'Category deleted successfully!');
    }
}
