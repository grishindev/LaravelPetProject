<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // в переменную $categories закидываем все категории из таблицы Category
        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        Category::create($request->all());
        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category = Category::find($id); // Поиск записи по $id
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [ // Добавим валидацию
            'title' => 'required'
        ]);
        $category = Category::find($id);
        $category->update($request->all());
        return redirect()->route('categories.index'); // Возврат на страницу с листингом всех категорий
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect()->route('categories.index');
    }
}
