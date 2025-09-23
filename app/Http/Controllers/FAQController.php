<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQModel;
use App\Models\TermConditionsModel;
use App\Models\RefundCancellation;

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
        $faqData->faq_category = $request->faq_category;
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
        $faqData->faq_category = $request->faq_category;
        $faqData->update();
        return redirect()->route('faq-list')->with('success', 'faq Updated Successfully');
    }

    public function termConditionsAdmin(Request $request)
    {
          $privacyTerms = TermConditionsModel::first();
          return view('admin.term_conditions.term_conditions',compact('privacyTerms'));
    }

    public function term_conditionsStore(Request $request)
    {
        if (isset($request->content)) {
            $privacyPolicy = TermConditionsModel::first();
            if (empty($privacyPolicy)) {
                $privacyPolicy = new TermConditionsModel;
            }
            $privacyPolicy->content = $request->content;
            $privacyPolicy->save();
        }

        return redirect()->route('term.conditions.admin')->with('success', 'Term Conditions Updated Successfully');
    }
  
    public function refundCancellationData(Request $request)
    {
        $privacyTerms = RefundCancellation::first();
        return view('admin.refund_cancellation',compact('privacyTerms'));
    }
  
   public function refundCancellationStore(Request $request)
   {
      if (isset($request->refund_cancellation)) {
            $privacyPolicy = RefundCancellation::first();
            if (empty($privacyPolicy)) {
                $privacyPolicy = new RefundCancellation;
            }
            $privacyPolicy->refund_cancellation = $request->refund_cancellation;
            $privacyPolicy->save();
        }

        return redirect()->route('refund-cancellation-data')->with('success', 'Refund Cancellation Updated Successfully');
   }
}
