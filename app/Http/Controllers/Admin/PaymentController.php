<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    //this function use create payment
    public function paymentDetails(Request $request)
    {
             try{
     
                $detailsList = PaymentModel::all();
                return view('admin.payment.index', compact('detailsList'));

             } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
    }

    //this function use create payment
    public function PaymentCreate(Request $request)
    {
            try{

                return view('admin.payment.create');
             } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
    }

    //this function use payment Store
    public function paymentStore(Request $request)
    {
        try{

                $validator = Validator::make($request->all(), [
                    'payment_name' => 'required',
                    'payment_image' => 'required',
                ], [
                    'payment_name.required'=>'Enter Name',
                    'payment_image.required' => 'Enter Image',
                ]);

                if ($validator->fails()) {
                    $error = $validator->getMessageBag();
                    return redirect()->back()->with('error', $error->first());
                }

                    // Move uploaded file to public path
                    $paymentNew = new PaymentModel;
                    $paymentNew->payment_method = $request->payment_name;
                    if (!empty($request->payment_image)) {
                      $dark_logo    = $request->payment_image;
                      $filename = time() . $dark_logo->getClientOriginalName();
                      $dark_logo->move(public_path('payment_image'), $filename);
                      $paymentNew->icon = $filename;
                  }
                  $paymentNew->save();

            return redirect()->route('payment.details')->with('success', 'Payment Method Added Successfully');
         } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use delete payment method
    public function paymentDelete($id)
    {
            try{
             

               $paymentMethod = PaymentModel::where('id',$id)->first();
               if(!empty($paymentMethod))
               {
                $paymentMethod->delete();
               }

               return redirect()->route('payment.details')->with('success', 'Payment Method Deleted Successfully');
            } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //this function use edit payment
    public function paymentEdit($id)
    {
          try{

            $paymentMethod = PaymentModel::where('id',$id)->first();

              return view('admin.payment.edit',compact('paymentMethod'));
           } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //thsi function use update oayment
    public function paymentUpdate(Request $request)
    {
          try{

            $validator = Validator::make($request->all(), [
                'payment_name' => 'required',
                // 'payment_image' => 'required',
            ], [
                'payment_name.required'=>'Enter Name',
                'payment_image.required' => 'Enter Image',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return redirect()->back()->with('error', $error->first());
            }

                // Move uploaded file to public path
                $paymentNew = PaymentModel::find($request->id);
                $paymentNew->payment_method = $request->payment_name;
                if (!empty($request->payment_image)) {
                  $dark_logo    = $request->payment_image;
                  $filename = time() . $dark_logo->getClientOriginalName();
                  $dark_logo->move(public_path('payment_image'), $filename);
                  $paymentNew->icon = $filename;
              }
              $paymentNew->update();

        return redirect()->route('payment.details')->with('success', 'Payment Method Updated Successfully');

           } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
