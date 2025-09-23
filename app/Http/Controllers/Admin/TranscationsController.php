<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Banner;


class TranscationsController extends Controller
{
    //this function use transction
    public function transctionData(Request $request)
    {
        try {
            $url = url('/') . '/api/transcations-list';
            $response = Http::get($url);
            $data = $response->json();
            $detailsList = [];
            if (isset($data['data'])) {
                $detailsList = $data['data'];
            }

            return view('admin.transcations.index', compact('detailsList'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function bannerIndex(Request $request)
    {
        $bannerData = Banner::get();
        return view('admin.banner.index',compact('bannerData'));
    }
    
    public function bannerCreate(Request $request)
    {
        return view('admin.banner.create');
    }
    
    public function bannerDelete($id)
    {
        $bannerData = Banner::where('id',$id)->first();
        if(isset($bannerData))
        {
            $bannerData->delete();
        }
       return redirect()->back()->with('success', 'Banner Deleted Successfully');
    }
    
    public function bannerEdit($id)
    {
        $bannerData = Banner::find($id);
        return view('admin.banner.edit',compact('bannerData'));
    }
    
    public function bannerStore(Request $request)
    {
         $banner = new Banner;
        if (!empty($request->banner)) {
            $image    = $request->banner;
            $filename = time() . $image->getClientOriginalName();
            $image->move(public_path('image'), $filename);
            $banner->banner = $filename;
        }
        $banner->save();
         return redirect()->route('banner.index')->with('success', 'Banner Added Successfully');
    }
    
      public function bannerUpdate(Request $request)
    {
         $banner = Banner::find($request->id);
        if (!empty($request->banner)) {
            $image    = $request->banner;
            $filename = time() . $image->getClientOriginalName();
            $image->move(public_path('image'), $filename);
            $banner->banner = $filename;
        }
        $banner->update();
         return redirect()->route('banner.index')->with('success', 'Banner Updated Successfully');
    }
    
    // 
        
}
