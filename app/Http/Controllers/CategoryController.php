<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function index()
    {
        $categories = Category::where('parent_id', null)->with('children')->get();
        $lastId = Category::orderBy('id', 'desc')->first()->id;
        $data = [
            'categories'  => $categories,
            'lastId'   => $lastId,
        ];
        return view('welcome')->with($data);
    }

    /**
     * change categories and subcategories languages
     **/
    public function lang(Request $request){
        $name = $request['name'];
        $categories = Category::select('id',$name.' AS name')->where('parent_id', null)->with(array('children' => function ($query) use ($name) {
           return $query->select('id','parent_id',$name.' AS name')->get();
        }))->get();
        return $categories;

    }


    /**
     * Create new category
     **/
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_eng' => 'required|string|max:255',
            'name_rus' => 'required|string|max:255',
        ]);
        $category = new Category();
        $category->name = $request['name'];
        $category->eng_name = $request['name_eng'];
        $category->rus_name = $request['name_rus'];
        $category->parent_id = $request['parent_id'];
        $category->save();


        return $category;
    }

    /**
     * update category position
     **/
    public function update(Request $request, $id = null)
    {
        Category::where('id', $request['id'])->update([
            'parent_id' => $request['parent_id']
        ]);

        return true;
    }


}
