<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriptionPlan;
use App\Models\OfflineRequestsModel;
use App\Models\BlogModel;
use App\Models\CategoryModel;
use App\Models\BlogComment;
use App\Models\User;
use App\Models\PharmaShop;
use Illuminate\Support\Facades\Hash;
use App\Models\LogsModel;
use Illuminate\Support\Facades\Http;

class MenuController extends Controller
{
    // this function use product features index
    public function productFeaturesIndex()
    {
        try {
            return view('menu.product_features');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use pricing index
    public function pricingIndex()
    {
        try {
            $suscriptionData = SubscriptionPlan::get();

            return view('menu.pricing', compact('suscriptionData'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use demotrain index
    public function demotrainIndex()
    {
        try {
            $plan = SubscriptionPlan::get();
            return view('menu.demotrain', compact('plan'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use contactus index
    public function contactusIndex()
    {
        try {
        
            return view('menu.contactus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use aboutus index
    public function aboutusIndex()
    {
        try {
            return view('menu.aboutus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use referandearn index
    public function referandearnIndex()
    {
        try {
            return view('menu.referandearn');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use blogs index
    public function blogsIndex(Request $request)
    {
        try {
            // Paginate the blogs, 4 per page
            if ($request->has('id')) {
                $blogs = BlogModel::where('category_id', $request->id)
                    ->paginate(4)
                    ->appends(['id' => $request->id]); // Append query for pagination
            } elseif ($request->has('tag')) {
                $blogs = BlogModel::where('tags', 'like', '%' . $request->tag . '%')
                    ->paginate(4)
                    ->appends(['tag' => $request->tag]); // Append query for pagination
            } else {
                $blogs = BlogModel::paginate(4);
            }

            $blogsLatest = BlogModel::latest()->take(3)->get();
            $blogTags = BlogModel::orderBy('id', 'DESC')->first();
            $categoryData = CategoryModel::latest()->get();

            return view('menu.blogs', compact('blogs', 'blogsLatest', 'blogTags', 'categoryData'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function blogComment(Request $request)
    {
        
        //   $blogComment = BlogComment::get();
          $logNewData = new BlogComment;
          $logNewData->blog_id = $request->blog_id;
          $logNewData->name = $request->name;
          $logNewData->phone_number = $request->phone_number;
          $logNewData->email_address = $request->email_address;
          $logNewData->messages = $request->textarea;
          $logNewData->save();

          return redirect()->back();
    }


    public function loadMore(Request $request)
    {
        // Get the next set of blog posts, based on the skip value.
        $blogs = BlogModel::skip($request->skip)->take(5)->get(); // Adjust number as per your needs

        
        if ($blogs->isEmpty()) {
            return response()->json(['status' => 'no_more']);
        }

        return response()->json(['status' => 'success', 'blogs' => $blogs]);
    }

    public function termConditions(Request $request)
    {
        return view('menu.term_conditions');
    }

    // this function use privacypolicy index
    public function privacypolicyIndex()
    {
        try {
            return view('menu.privacypolicy');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use cancellationpolicy index
    public function cancellationpolicyIndex()
    {
        try {
            return view('menu.cancellationpolicy');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this function use book training index
    public function bookTrainingIndex()
    {
        try {
            return view('menu.book_training');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function singleblogData($title)
    {
        // Perform the database query with case-insensitive matching using the correct collation for utf8mb4
        $blogs = BlogModel::where('slug', $title)->first();
    
        $blogsRecent = BlogModel::where('slug','!=', $title)->latest()->take(2)->get();

        $blogDatas = BlogComment::where('blog_id', $blogs->id)->paginate(5); // Change 5 to desired per-page limit

        return view('menu.singleblog',compact('blogs','blogsRecent','blogDatas'));
    }

    // this function use contact us data store
    public function contactusStore(Request $request)
    {
        try {
            if (isset($request->name) && isset($request->email) && isset($request->phone)) {
                
                $contact_us_data = new SupportTicket();
                $contact_us_data->name = $request->name;
                $contact_us_data->email = $request->email;
                $contact_us_data->phone = $request->phone;
                $contact_us_data->title = $request->subject;
                $contact_us_data->address = $request->address;
                $contact_us_data->message = $request->message;
                $contact_us_data->save();

                return redirect()->back()->with('success', 'Contact Us Data Added Successfully.');
            } else {
                return redirect()->back()->with('error', 'Please Filled Some Fields.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
  	
    public function frontindex()
    {
        $subscriptioPlan = SubscriptionPlan::take(3)->get();

        return view('front.index', compact('subscriptioPlan'));
    }

    public function insertContact(Request $request)
    {

              $subscriptionData = SubscriptionPlan::first();
      
              $officeData = new OfflineRequestsModel;
              $officeData->subscription_plan = $request->plan;
              $officeData->pharma_id = '';
              $officeData->submitted_by = '';
              $officeData->plan_type = isset($subscriptionData->id) ? $subscriptionData->id : "";
              $officeData->payment_method = 'Offline';
              $officeData->name = $request->name;
              $officeData->submitted_on = $request->data;
              $officeData->time = $request->time;
              $officeData->email = $request->email;
              $officeData->phone = $request->phone;
              $officeData->address = $request->address;
              $officeData->message = $request->message;
              $officeData->status = '0';
              $officeData->save();

               $pharmaUser = new User;
               $pharmaUser->email = $request->email;
               $pharmaUser->name = $request->name;
                $pharmaUser->password = Hash::make("Pharma247");
               $pharmaUser->phone_number = $request->phone;
               $pharmaUser->user_referral_code = substr(md5(mt_rand()), 0, 7);
               $pharmaUser->status = '1';
               $pharmaUser->role = '1';
               $pharmaUser->save();

        return response()->json(['success' => true]);
    }

    public function readyToGetStore(Request $request)
    {
   
        try {
     
            if (isset($request->name) && isset($request->email) && isset($request->phone) && isset($request->message)) {
            
                   $subscriptionData = SubscriptionPlan::first();
                  $readyToGetData = new OfflineRequestsModel();
                  $readyToGetData->pharma_id = '';
                  $readyToGetData->submitted_by = '';
                  $readyToGetData->subscription_plan = isset($subscriptionData->id) ? $subscriptionData->id : "";
                  $readyToGetData->payment_method = 'Offine';
                  $readyToGetData->submitted_on = date('Y-m-d');
                  $readyToGetData->time = $request->time;
                  $readyToGetData->data = $request->date;
                  $readyToGetData->status = '0';
                  $readyToGetData->name = $request->name;
                  $readyToGetData->email = $request->email;
                  $readyToGetData->phone = $request->phone;
                  //$readyToGetData->subscription_plan = $request->plan;
                  $readyToGetData->message = $request->message;
                  $readyToGetData->save();  

                    $url = 'https://web.wabridge.com/api/createmessage';

                    $payload = [
                        'app-key'       => 'db8ce965-029b-4f74-aade-04d137663b12',
                        'auth-key'      => '039d46d11eab7e7863eb651db09f8eac63198154bf41302430',
                        'destination_number' =>  '91'.$request->phone,
                        'template_id'   => '1603184876841099', 
                        'device_id'     => '6747f73e1bcbc646dbdc8c5f', 
                        'variables'     => [
                            $request->name, 
                            $request->date,
                            $request->time,
                            "https://meet.google.com/cdz-xhub-apw",
                        ],
                    ];

                    // Send the request using Laravel HTTP client
                    $response = Http::post($url, $payload);
              
                return redirect()->back()->with('success', 'Data Added Successfully.');
            } else {
                  $subscriptionData = SubscriptionPlan::first();
                  $readyToGetData = new OfflineRequestsModel();
                  $readyToGetData->subscription_plan = isset($subscriptionData->id) ? $subscriptionData->id : "";
                  $readyToGetData->payment_method = 'Offine';
                  $readyToGetData->submitted_on = date('Y-m-d');
                  $readyToGetData->phone = $request->phone;
                  $readyToGetData->save();  
                return redirect()->back()->with('error', 'Please Filled Some Fields.');
            }
        } catch (\Exception $e) {
        
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}