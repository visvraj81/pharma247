<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\ItemCategory;
use App\Models\IteamsModel;
use App\Models\User;
use App\Models\PharmaShop;
use App\Models\PrivacyPolicy;
use App\Models\SliderModel;
use App\Models\Setting;
use App\Models\MetaPage;
use App\Models\BlogModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->role == '1') {
            $package = Package::all();
            $iteamCatgeory = ItemCategory::all();
            $iteamAdd  = IteamsModel::whereNull('user_id')->orWhere('user_id',auth()->user()->id)->get();
            $distributer = User::where('role', '4')->get();
            $pahrma = PharmaShop::all();

            $iteamAddList = null;
            if (isset($request->search)) {
                $iteamAddList = IteamsModel::whereNull('user_id')->orWhere('user_id',auth()->user()->id)->where('id', $request->search)->first();
            }
            return view('pharma.pharma', compact('package', 'iteamCatgeory', 'iteamAdd', 'iteamAddList', 'distributer', 'pahrma'));
        } else {
          	$pharmaCount = User::orderBy('id', 'DESC')->where('role','!=','10')->count();
          	$blogCount = BlogModel::count();
          	$usersCount = User::orderBy('id', 'DESC')->where('role',"!=",'0')->whereNull('user_id')->count();
          	$itemsCount = IteamsModel::orderBy('id', 'DESC')->count();
            return view('home',compact('pharmaCount','blogCount','usersCount','itemsCount'));
        }
    }

      public function pageMetaTags(Request $request)
    {
        $pageMetaTags = MetaPage::all();
       return view('admin.page_title',compact('pageMetaTags'));
    }

    public function updatePageMeta(Request $request)
    {
        foreach ($request->ids as $id) {
            MetaPage::where('id', $id)->update([
                'meta_title' => $request->meta_title[$id] ?? null,
                'meta_description' => $request->meta_description[$id] ?? null,
                'meta_keywords' => $request->meta_keywords[$id] ?? null,
                'og_title' => $request->og_title[$id] ?? null,
                'og_description' => $request->og_description[$id] ?? null,
                'tiwter_title' => $request->twitter_title[$id] ?? null,
                'tiwter_description' => $request->twitter_description[$id] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Page meta tags updated successfully!');
    }
  
    public function privacyPolicy(Request $request)
    {
        $privacyProlicy = PrivacyPolicy::first();
        return view('privacy_policy', compact('privacyProlicy'));
    }

    public function privacyPolicyStore(Request $request)
    {
        if (isset($request->content)) {
            $privacyPolicy = PrivacyPolicy::first();
            if (empty($privacyPolicy)) {
                $privacyPolicy = new PrivacyPolicy;
            }
            $privacyPolicy->description = $request->content;
            $privacyPolicy->save();
        }


        return redirect()->route('privacy-policy')->with('success', 'Privacy Policy Updated Successfully');
    }

    public function sliderIndex(Request $request)
    {
        $sliderData = SliderModel::get();
         return view('admin.slider.index',compact('sliderData'));
    }

    public function sliderCreate(Request $request)
    {
         
        return view('admin.slider.create');
    }

    public function sliderStore(Request $request)
    {
         
          $SliderModel = new SliderModel;
          if (!empty($request->banner)) {
            $image    = $request->banner;
            $filename = time() . $image->getClientOriginalName();
            $image->move(public_path('image'), $filename);
            $SliderModel->image = $filename;
        }
        $SliderModel->title = $request->title;
        $SliderModel->description = $request->description;
        $SliderModel->save();

        return redirect()->route('slider.index')->with('success', 'Slider Added Successfully');
    }

    public function sliderEdit($id)
    {
        $sliderData = SliderModel::where('id',$id)->first();
        return view('admin.slider.edit',compact('sliderData'));
    }

    public function sliderDelete($id)
    {
        $slderData = SliderModel::where('id',$id)->first();
        if(isset($slderData))
        {
            $slderData->delete();
        }

        return redirect()->route('slider.index')->with('success', 'Slider Deleted Successfully');
    }

    public function sliderUpdate(Request $request)
    {
           $SliderModel = SliderModel::find($request->slider_id);
           if (!empty($request->banner)) {
             $image    = $request->banner;
             $filename = time() . $image->getClientOriginalName();
             $image->move(public_path('image'), $filename);
             $SliderModel->image = $filename;
         }
         $SliderModel->title = $request->title;
         $SliderModel->description = $request->description;
         $SliderModel->update();
 
         return redirect()->route('slider.index')->with('success', 'Slider Updated Successfully');
    }

    public function videoIndex(Request $reques)
    {
        $settingData = Setting::first();
       return view('admin.video.index',compact('settingData'));
    }
}
