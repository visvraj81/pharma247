<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryModel;

class BlogCategoryController extends Controller
{
    //
    public function blogCategory(Request $request)
    {
        $blogCategory = CategoryModel::get();
        return view('admin.category.index',compact('blogCategory'));
    }

    public function categorysCreate(Request $request)
    {
         return view('admin.category.create');
    }

    public function categoryssStore(Request $request)
    {
        $blogData = new CategoryModel;
        $blogData->categories = $request->categories;
        $blogData->save();

        return redirect()->route('blog.category')->with('success', 'Blog Category Added Successfully');
    }

    public function categorysDelete($id)
    {
       $blogDelete = CategoryModel::find($id);
      if(isset($blogDelete))
      {
        $blogDelete->delete();
      }
       return redirect()->route('blog.category')->with('success', 'Blog Category Deleted Successfully');
    }

    public function editCategory($id)
    {
        $bannerData = CategoryModel::find($id);
        return view('admin.category.edit',compact('bannerData'));
    }

    public function categorysUpdate(Request $request)
    {
          $categoryDatas = CategoryModel::where('id',$request->id)->first();
          if(isset($categoryDatas))
          {
            $categoryDatas->categories = $request->categories;
            $categoryDatas->update();
          }
          return redirect()->route('blog.category')->with('success', 'Blog Category Updated Successfully');
    }
}
