<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\CategoryModel;

class BlogController extends Controller
{
    //
    public function blogList(Request $request)
    {
        $blogData = BlogModel::get();
        return view('admin.blog.index',compact('blogData'));
    }

    public function blogCreate(Request $request)
    {
        $CategoryData = CategoryModel::get();
        return view('admin.blog.create',compact('CategoryData'));
    }

    public function blogsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:blog,title', 
        ], [
            'title.unique' => 'The title has already been taken.', // Custom error message
        ]);

        $blogData = new BlogModel;
        $blogData->title = $request->title;
        $blogData->description = $request->description;
        $blogData->category_id = $request->category_id;
        $blogData->tags = $request->tags;
        $blogData->sort_descrption = $request->sort_description;
        $blogData->slug =\Str::slug($request->title);
        $blogData->key_word = $request->key_word;
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Ensure the upload directory exists
            $uploadPath = public_path('uploads/students/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            $originalName = $file->getClientOriginalName();
            // Generate a unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
    
            // Move the file to the upload directory
            $file->move($uploadPath, $filename);
    
            // Save the filename in the database
            $blogData->image =  $filename;
        }
    
        // Save the blog data to the database
        $blogData->save();
        return redirect()->route('blog-list')->with('success', 'Blog Added Successfully');
    }

    public function blogDelete($id)
    {
       $blogDelete = BlogModel::find($id);
      if(isset($blogDelete))
      {
        $blogDelete->delete();
      }
       return redirect()->route('blog-list')->with('success', 'Blog Deleted Successfully');
    }

    public function blogeditCreate($id)
    {
        $blogDelete = BlogModel::find($id);
        $CategoryData = CategoryModel::get();
        return view('admin.blog.edit',compact('blogDelete','CategoryData'));
    }

    public function blogUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:blog,title,' . $request->id,
        ], [
            'title.unique' => 'The title has already been taken.', // Custom error message
        ]);
        
        $blogData = BlogModel::where('id',$request->id)->first();
        $blogData->title = $request->title;
        $blogData->description = $request->description;
        $blogData->category_id = $request->category_id;
        $blogData->sort_descrption = $request->sort_description;
        $blogData->key_word = $request->key_word;
        $blogData->tags = $request->tags;
        $blogData->slug =\Str::slug($request->title);
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Ensure the upload directory exists
            $uploadPath = public_path('uploads/students/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $originalName = $file->getClientOriginalName();
          
            // Generate a unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName . '.' . $extension;
    
            // Move the file to the upload directory
            $file->move($uploadPath, $filename);
    
            // Save the filename in the database
            $blogData->image =  $filename;
        }
    
        // Save the blog data to the database
        $blogData->update();
        return redirect()->route('blog-list')->with('success', 'Blog Updated Successfully');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
    
            $request->file('upload')->move(public_path('media'), $fileName);
    
            $url = asset('/public/media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
        return response()->json(['uploaded' => 0, 'error' => ['message' => 'File upload failed.']]);
    }
}