<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQModel;

class FAQController extends Controller
{
    public function faqList(Request $request)
    {
        $faqData = FAQModel::get();
        return view('admin.faq.index',compact('faqData'));
    }

    public function faqCreate(Request $request)
    {
        return view('admin.faq.create');
    }

    public function faqsStore(Request $request)
    {
        $faqData = new FAQModel;
        $faqData->question = $request->question;
        $faqData->answer = $request->answer;
        $faqData->save();
        return redirect()->route('faq-list')->with('success', 'faq Added Successfully');
    }

    public function faqDelete($id)
    {
       $faqDelete = FAQModel::find($id);
      if(isset($faqDelete))
      {
        $faqDelete->delete();
      }
       return redirect()->route('faq-list')->with('success', 'faq Deleted Successfully');
    }

    public function faqeditCreate($id)
    {
        $faqDelete = FAQModel::find($id);
        return view('admin.faq.edit',compact('faqDelete'));
    }

    public function faqUpdate(Request $request)
    {
        $faqData = FAQModel::where('id',$request->id)->first();
        $faqData->question = $request->question;
        $faqData->answer = $request->answer;
        $faqData->update();
        return redirect()->route('faq-list')->with('success', 'faq Updated Successfully');
    }
}
