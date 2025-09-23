<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\IteamsModel;
use App\Models\ItemCategory;
use App\Models\Package;

class IteamController extends Controller
{
    //this function use iteam create
    public function iteamCreate(Request $request)
    {
        try{

                  $validator = Validator::make($request->all(), [
                    'iteam_name' => 'required',
                    'old_unit' => 'required',
                    'pack' => 'required',
                    'pahrma' => 'required',
                    'distrubuter' => 'required',
                    'drug_group' => 'required',
                    'item_category_id' => 'required',
                    'item_type' => 'required',
                    'gst' => 'required',
                    'loaction' => 'required',
                    'schedule' => 'required',
                    'add_text_not_applicable' => 'required',
                    'tax' => 'required',
                    'minimum' => 'required',
                    'maximum' => 'required',
                    'discount' => 'required',
                    'margin' => 'required',
                    'front_photo' => 'required',
                    'backside' => 'required',
                    'mrp_photo' => 'required',
                ], [
                    'iteam_name.required'=>'Please Enter Iteam Name',
                    'old_unit.required'=>'Please Enter Old Unit',
                    'pack.required'=>'Please Enter Pack',
                    'pahrma.required'=>'Please Select Pharma',
                    'distrubuter.required'=>'Please Enter Distrubuter',
                    'drug_group.required'=>'Please Enter Drug Group',
                    'item_category_id.required'=>'Please Select Item Category',
                    'item_type.required'=>'Please Select Item Type ',
                    'gst.required'=>'Please Enter GST',
                    'loaction.required'=>'Please Enter Location',
                    'schedule.required'=>'Please Enter Schedule',
                    'add_text_not_applicable.required'=>'Please Enter add_text_not_applicable',
                    'tax.required'=>'Please Enter Tax',
                    'minimum.required'=>'Please Enter Minimum',
                    'maximum.required'=>'Please Enter Maximum',
                    'discount.required'=>'Please Enter Discount',
                    'margin.required'=>'Please Enter Margin',
                    'front_photo.required'=>'Please Enter Front Photo',
                    'backside.required'=>'Please Enter Front Backside',
                    'mrp_photo.required'=>'Please Enter Mrp Photo',
                ]);
    
                if ($validator->fails()) {
                    $error = $validator->getMessageBag();

                    return redirect()->back()->with('error', $error->first());
                }

                 $front_photo = $request->front_photo;
                $filename = 'front_photo' . time() . '.png';
                $front_photo->move(public_path('front_photo'), $filename);

                $backside = $request->backside;
                $filenameBackData = 'backside' . time() . '.png';
                $backside->move(public_path('backside'), $filenameBackData);

                
                $mrp_photo = $request->mrp_photo;
                $filenameSlatData = 'mrp_photo' . time() . '.png';
                $mrp_photo->move(public_path('mrp_photo'), $filenameSlatData);
    
                $url = url('/') . '/api/create-iteams';
                $data = [
                    'iteam_name' => $request->iteam_name,
                    'old_unit' => $request->old_unit,
                    'packing_type' => $request->pack,
                    'pahrma' => $request->pahrma,
                    'distrubuter' => $request->distrubuter,
                    'drug_group' => $request->drug_group,
                    'item_category_id' => $request->item_category_id,
                    'item_type' => $request->item_type,
                    'gst' => $request->gst,
                    'barcode' => $request->barcode,
                    'schedule' => $request->schedule,
                    'tax_not_applied' => $request->tax_not_applied,
                    'tax' => $request->tax,
                    'minimum' => $request->minimum,
                    'maximum' => $request->maximum,
                    'discount' => $request->discount,
                    'margin' => $request->margin,
                    'front_photo' => $filename,
                    'backside' => $filenameBackData,
                    'mrp_photo' => $filenameSlatData,
                ];
    
                // dD($data);
                // Make the HTTP POST request
                $response = Http::post($url, $data);
    
                $responseData = $response->json();
                return redirect()->route('home')->with('success', 'Item Added Successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function iteamAdd(Request $request)
    { 
          try{

            $package = Package::all();
            $iteamCatgeory = ItemCategory::all();
            $iteamAdd  = IteamsModel::whereNull('user_id')->orWhere('user_id',auth()->user()->id)->get();
            return view('pharma.add_iteam',compact('iteamCatgeory','package'));
          } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function iteamEdit($id)
    {
          try{

            $url = url('/') . '/api/edit-iteam';
            $data = [
                'id' => $id,
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);

            $responseData = $response->json();
            $editDetails = [];
            if (isset($responseData['data'])) {
                $editDetails = $responseData['data'];
            }
            $iteamCatgeory = ItemCategory::all();

            $url = url('/') . '/api/batch-list';
            $data = [
                'iteam_id' => $id,
            ];

            // Make the HTTP POST request
            $response = Http::post($url, $data);
            $data = $response->json();
            $detailsList = [];
            if (isset($data['data'])) {
              $detailsList = $data['data'];
            }

            return view('pharma.edit_iteam',compact('editDetails','iteamCatgeory','detailsList'));

          } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //this function use item update
    public function iteamUpdate(Request $request)
    {
            try{

                    $url = url('/') . '/api/update-iteam';
                    $data = [
                        'id' => $request->id,
                        'location' => $request->location,
                        'default_disc' => $request->default_disc,
                        'min_qty' => $request->minimum,
                        'max_qty' => $request->maximum,
                        'gst' => $request->gst,
                        'accept_online_order' => $request->accept_online_order,
                        'item_category_id' => $request->item_category_id,
                        'cess' => $request->cess,
                        'hsn_code' => $request->hsn_code,
                        'manage_type' => $request->manage_type,
                        'item_alias' => $request->item_alias,
                        'morning_dose' => $request->morning_dose,
                        'afternoon_dose' => $request->afternoon_dose,
                        'evening_dose' => $request->evening_dose,
                        'nigte_dose' => $request->nigte_dose,
                    ];
        
                    $response = Http::post($url, $data);
        
                    $responseData = $response->json();
                    $editDetails = [];
                    if (isset($responseData['data'])) {
                        $editDetails = $responseData['data'];
                    }
                         
                    return redirect()->back()->with('success', 'Item Updated Successfully');
            } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

 public function iteamBlukAdd(Request $request)
    {
       return view('admin.iteam.create');
    }
  
    public function iteamStoreBulk(Request $request)
    {
        try {

            $file = $request->iteam_data;
            $filePath = $file->getRealPath();

            $data = array_map('str_getcsv', file($filePath));
            array_shift($data);

            if (isset($data)) {
                foreach ($data as $list) {
                   
                    $iteamsData = new IteamsModel;
                    $iteamsData->iteam_name = isset($list[0]) ? $list[0] : "";
                    $packageData = Package::where('packging_name', $list[1])->first();
                    if (isset($packageData)) {
                        $packageId = $packageData->id;
                    } 
                   //else {
                     //   if (isset($list[1])) {
                       //     $package = new Package;
                         //   $package->packging_name = isset($list[1]) ? $list[1] : "";
                          //  $package->save();
                           // $packageId = $package->id;
                        //}
                    //}

                    $iteamsData->packaging_id = isset($packageId) ? $packageId : "";
                    $uniteData = UniteTable::where('name', $list[2])->first();
                    if (isset($uniteData)) {
                        $uniteId = $uniteData->id;
                    } else {
                        if (isset($list[2])) {
                            $unit = new UniteTable;

                            $unit->name = isset($list[2]) ? $list[2] : "";
                            $unit->package_id = isset($iteamsData->packaging_id) ? $iteamsData->packaging_id : "";
                            $unit->save();
                            $uniteId = $unit->id;
                        }
                    }
                    $iteamsData->old_unit = isset($uniteId) ? $uniteId : "";
                    $iteamsData->unit = isset($list[3]) ? $list[3] : "";
                    $iteamsData->packing_size =  isset($list[4]) ? $list[4] : "";

                    $companyData = CompanyModel::where('company_name', $list[5])->first();
                    if (isset($companyData)) {
                        $companyId = $companyData->id;
                    } 
                    else {
                      if (isset($list[5])) {
                           $company = new CompanyModel;
                            $company->company_name = isset($list[5]) ? $list[5] : "";
                            $company->save();
                           $companyId = $company->id;
                      }
                    }
                    $iteamsData->pharma_shop = isset($companyId) ? $companyId : "";

                    if (isset($list[6])) {
                        $distributerData = Distributer::where('id', $list[6])->first();
                        if (isset($distributerData)) {
                            $distributerId = $distributerData->id;
                        } else {
                            if ($list[6] != "") {
                                $distributorData = new Distributer;
                                $distributorData->name = isset($list[6]) ? $list[6] : "";
                                $distributorData->status = '1';
                                $distributorData->role = '4';
                                $distributorData->save();
                                $distributerId = $distributorData->id;
                            }
                        }
                        $iteamsData->distributer_id = isset($distributerId) ? $distributerId : "";
                    }
                    $getData = GstModel::where('name', $list[7])->first();
                    if (isset($getData)) {
                        $gstId = $getData->id;
                    } 
                    $iteamsData->gst = isset($gstId) ? $gstId : "";

                    $categoryData = ItemCategory::where('category_name', $list[8])->first();
                    if (isset($categoryData)) {
                        $catgeoyrId = $categoryData->id;
                    } 
       
                    $iteamsData->item_category_id = isset($catgeoyrId) ? $catgeoyrId : "";
                   
                    $drugGroup = DrugGroup::where('name', $list[9])->first();
                    if (isset($drugGroup)) {
                        $drugGroupId = $drugGroup->id;
                    } else {
                        if (isset($list[1])) {
                            $drugs = new DrugGroup;
                            $drugs->name = isset($list[9]) ? $list[9] : "";
                            $drugs->save();
                            $drugGroupId = $drugs->id;
                        }
                    }
                    $iteamsData->drug_group = isset($drugGroupId) ? $drugGroupId : "";
                    
                    $iteamsData->accept_online_order = isset($list[11]) ? $list[11] : "";
                    $iteamsData->mrp = isset($list[12]) ? $list[12] : "";
                    $iteamsData->barcode = isset($list[13]) ? $list[13] : "";
                    $iteamsData->hsn_code = isset($list[14]) ? $list[14] : "";
                    $iteamsData->message = isset($list[15]) ? $list[15] : "";
                    $iteamsData->prescription_required = isset($list[16]) ? $list[16] : "";
                    $iteamsData->label = isset($list[17]) ? $list[17] : "";
                    $iteamsData->fact_box = isset($list[18]) ? $list[18] : "";
                    $iteamsData->primary_use = isset($list[19]) ? $list[19] : "";
                    $iteamsData->storage = isset($list[20]) ? $list[20] : "";
                    $iteamsData->use_of = isset($list[21]) ? $list[21] : "";
                    $iteamsData->common_side_effect = isset($list[22]) ? $list[22] : "";
                    $iteamsData->alcohol_Interaction = isset($list[23]) ? $list[23] : "";
                    $iteamsData->pregnancy_Interaction = isset($list[24]) ? $list[24] : "";
                    $iteamsData->lactation_Interaction = isset($list[25]) ? $list[25] : "";
                    $iteamsData->driving_Interaction     = isset($list[26]) ? $list[26] : "";
                    $iteamsData->kidney_Interaction     = isset($list[27]) ? $list[27] : "";
                    $iteamsData->liver_Interaction     = isset($list[28]) ? $list[28] : "";
             
                    $iteamsData->country_of_origin     = isset($list[30]) ? $list[30] : "";
                    $iteamsData->save();
         
                  if(isset($list[10]))
                  {
                    $itemLocation = new ItemLocation;
                    $itemLocation->user_id = auth()->user()->id;
                    $itemLocation->item_id =  $iteamsData->id;
                    $itemLocation->location = isset($list[10]) ? $list[10] : "";
                    $itemLocation->save();
                  }
                   
                }
            }
          
          return redirect()->back()->with('success', 'Item Bluk Upload Successfully');
        } catch (\Exception $e) {
            dD($e);
            Log::info("Iteams import api" . $e->getMessage());
            return $e->getMessage();
        }
    }
  
    public function iteamLists(Request $request)
    {
         $iteamDetails = IteamsModel::orderBy('id', 'DESC')->get();
         return view('admin.iteam.list',compact('iteamDetails'));
    }
  
    public function toggleRecommend(Request $request)
    {
       $iteamDatas = IteamsModel::where('id',$request->id)->first();
      if(isset($iteamDatas))
      {
          $iteamDatas->status = $request->recommended;
          $iteamDatas->update();
      }
      
      return response()->json(['success' => true]);
    }
}
