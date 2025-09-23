<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\ApiToken;
use App\Models\IteamsModel;
use App\Models\UniteTable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\AddCart;
use Illuminate\Support\Facades\Auth;
use App\Models\PrescrptionModel;
use App\Models\PatientsAddress;
use App\Models\PatientsFamilyModel;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\PatientsModel;
use App\Models\RecentIteamModel;
use Illuminate\Support\Str;
use App\Models\PatientsOrder;
use App\Models\NotificationModel;
use App\Models\PatientOrderItem;
use App\Models\PatientOrderImages;
use App\Models\OrderStatusData;
use App\Models\SetRefillReminder;
use App\Models\SetPillReminder;
use App\Models\SetPillReminderDays;
use App\Models\ChemistNotificationModel;

class PatientOrderController extends ResponseController
{ 
  public function patientOrder(Request $request)
  {
    $user = auth()->user();

    // Get cart items grouped by chemist
    $cartListData = AddCart::where('patient', $user->id)->get()->groupBy('chemist');

    // Determine family member type and ID
    $familyMemberType = $request->famliy_member_id == 'Self' ? 1 : 2;
    $familyId = $request->famliy_member_id == 'Self' ? $user->id : $request->famliy_member_id;

    $orderIds = [];

    // -----------------------------
    // 1. CART-BASED ORDER PROCESS
    // -----------------------------
    if ($cartListData->isNotEmpty()) {
      // Shared Order ID and Bill No
      $orderId = '#' . rand(10000, 99999);
      $billNo = 'BILL-' . rand(10000, 99999);

      foreach ($cartListData as $chemistId => $listData) {
        // Get order details and totals for this chemist
        $patientOrderDetailsList = AddCart::where('patient', $user->id)
          ->where('chemist', $chemistId)
          ->get();
        $patientOrderDetailsTotal = $patientOrderDetailsList->sum('total');

        // Create a new patient order
        $patientOrder = new PatientsOrder();
        $patientOrder->patient_id = $user->id;
        $patientOrder->order_id = $orderId;
        $patientOrder->bill_no = $billNo;
        $patientOrder->chemist_id = $chemistId ?? null;
        $patientOrder->address_id = $request->address_id ?? null;
        $patientOrder->famliy_member_id = $familyId;
        $patientOrder->family_member_type = $familyMemberType;
        $patientOrder->delivery_status = $request->delivery_status ?? '0';

        // Calculate rounding and net amount
        $originalAmount = $patientOrderDetailsTotal;
        $roundedAmount = round($originalAmount);
        $rounding_off = $roundedAmount - $originalAmount;
        $rounding_off = number_format($rounding_off, 2, '.', '');
        $net_amount = $originalAmount + $rounding_off;

        $patientOrder->round_off = $rounding_off;
        $patientOrder->new_amount = $net_amount;
        $patientOrder->total_amount = $patientOrderDetailsTotal;
        $patientOrder->notes = $request->note;
        $patientOrder->save();

        // Save each item in the order
        foreach ($patientOrderDetailsList as $list) {
            $patient_order_items = new PatientOrderItem();
            $patient_order_items->patient_order_id = $patientOrder->id;
            $patient_order_items->iteam_id = $list->iteam_id ?? null;
            $patient_order_items->price = $list->price ?? 0;
            $patient_order_items->qty = $list->qty;
            $patient_order_items->chemist_id = $chemistId;
            $patient_order_items->sub_amount = $list->price * $list->qty ?? '0';
            $patient_order_items->save();
        }

        // Attach prescriptions if available
        $prescriptions = PrescrptionModel::where('user_id', $user->id)
          ->where('status', 1)
          ->whereNull('order_id')
          ->get();

        foreach ($prescriptions as $prescription) {
          $patient_order_image_data = new PatientOrderImages();
          $patient_order_image_data->patient_order_id = $patientOrder->id;
          $patient_order_image_data->image = $prescription->images;
          $patient_order_image_data->user_id = $user->id;
          $patient_order_image_data->order_id = $orderId;
          $patient_order_image_data->save();
        }

        // Send notification to chemist
        $orderNotification = new ChemistNotificationModel();
        $orderNotification->title = 'Order Notification';
        $orderNotification->description = 'We have successfully received your order ' . $orderId;
        $orderNotification->user_id = $chemistId ?? $user->your_chemist;
        $orderNotification->order_id = $patientOrder->id;
        $orderNotification->save();

        $title = $orderNotification->title;
        $message = $orderNotification->description;
        // $userId = auth()->user()->id;
        $userId = $user->your_chemist;

        $this->chemist_notification($title, $message, $userId);

        $orderIds[] = $patientOrder->id;
      }

      // Delete prescriptions after use
      PrescrptionModel::where('user_id', $user->id)->where('status', 1)->delete();

      // Clear the user's cart
      AddCart::where('patient', $user->id)->delete();

      return $this->sendResponse([], 'Your Order Has Been Placed Successfully.');
    }

    // -----------------------------
    // 2. IMAGE-ONLY ORDER PROCESS
    // -----------------------------
    
    $prescriptions = PrescrptionModel::where('user_id', $user->id)
      ->where('status', 1)
      ->whereNull('order_id')
      ->get();

    if ($prescriptions->isNotEmpty()) {
      $orderId = '#' . rand(10000, 99999);
      $billNo = 'BILL-' . rand(10000, 99999);

      $patientOrder = new PatientsOrder();
      $patientOrder->patient_id = $user->id;
      $patientOrder->order_id = $orderId;
      $patientOrder->bill_no = $billNo;
      // $patientOrder->chemist_id = $user->your_chemist ?? null;
      $patientOrder->address_id = $request->address_id ?? null;
      $patientOrder->famliy_member_id = $familyId;
      $patientOrder->family_member_type = $familyMemberType;
      $patientOrder->delivery_status = $request->delivery_status ?? 'pending';
      $patientOrder->round_off = 0;
      $patientOrder->new_amount = 0;
      $patientOrder->total_amount = 0;
      $patientOrder->notes = $request->note;
      $patientOrder->save();

      foreach ($prescriptions as $prescription) {
        $patient_order_image_data = new PatientOrderImages();
        $patient_order_image_data->patient_order_id = $patientOrder->id;
        $patient_order_image_data->image = $prescription->images;
        $patient_order_image_data->user_id = $user->id;
        $patient_order_image_data->order_id = $orderId;
        $patient_order_image_data->save();
      }

      // Send Notification
      // $orderNotification = new ChemistNotificationModel();
      // $orderNotification->user_id = $user->your_chemist;
      // $orderNotification->order_id = $patientOrder->id;
      // $orderNotification->title = 'Order Notification';
      // $orderNotification->description = 'We have successfully received your prescription order ' . $orderId;
      // $orderNotification->save();

      // Delete prescriptions after use
      PrescrptionModel::where('user_id', $user->id)->where('status', 1)->delete();
      
      // $title = $orderNotification->title;
      // $message = $orderNotification->description;
      // $userId = auth()->user()->id;
      // $userId = $user->your_chemist;

      // $this->chemist_notification($title, $message, $userId);

      return $this->sendResponse([], 'Your Image-Based Order Has Been Placed Successfully.');
    }

    // -----------------------------
    // 3. NO ITEMS, NO PRESCRIPTION
    // -----------------------------
    return $this->sendError('No items or prescriptions found to place an order.');
  }

  public function patientReOrder(Request $request)
  {
    $patient_order_item_data = PatientOrderItem::where('patient_order_id', $request->id)->get();

    if (isset($patient_order_item_data)) {
      foreach ($patient_order_item_data as $list) {
        $item_data = IteamsModel::where('id', $list->iteam_id)->first();

        $add_cart_data = new AddCart();
        $add_cart_data->iteam_id = $list->iteam_id;
        $add_cart_data->qty = $list->qty;
        $add_cart_data->price = $item_data->mrp;
        $add_cart_data->total = $list->qty * $item_data->mrp;
        $add_cart_data->patient = auth()->user()->id;
        $add_cart_data->chemist = $list->chemist_id;
        $add_cart_data->save();
      }
    }

    $patient_order_image_data = PatientOrderImages::where('patient_order_id', $request->id)->get();
    if (isset($patient_order_image_data)) {
      foreach ($patient_order_image_data as $data) {
        $prescrption_image_data = new PrescrptionModel();
        $prescrption_image_data->images = $data->image;
        $prescrption_image_data->user_id = $data->user_id;
        $prescrption_image_data->status = 1;
        $prescrption_image_data->order_id = $data->order_id;
        $prescrption_image_data->save();
      }
    }

    return $this->sendResponse([], 'All items from your previous order have been successfully added to your cart.');
  }

  public function patientMyOrderList(Request $request)
  {
    $patientOrderData = PatientsOrder::where('patient_id', auth()->user()->id)->orderBy('id', 'DESC');
    if (isset($request->order_status)) {
      $patientOrderData->where('order_status', $request->order_status);
    }
    if (isset($request->famliy_member)) {
      $patientOrderData->where('famliy_member_id', $request->famliy_member);
    }
    if (isset($request->bill_number)) {
      $patientOrderData->where('bill_no', 'like', '%' . $request->bill_number . '%');
    }
    if (isset($request->order_number)) {
      $patientOrderData->where('order_id', 'like', '%' . $request->order_number . '%');
    }
    if (!empty($request->start_date) && !empty($request->end_date)) {
      $startDate = date('Y-m-d 00:00:00', strtotime($request->start_date));
      $endDate = date('Y-m-d 23:59:59', strtotime($request->end_date));

      $patientOrderData->whereBetween('created_at', [$startDate, $endDate]);
    }
    $patientOrderData = $patientOrderData->get();

    $orderDeatilsList = [];
    if (isset($patientOrderData)) {
      foreach ($patientOrderData as $key => $listDetails) {
        $orderStatusData = OrderStatusData::where('id', $listDetails->order_status)->first();
        //$statusData = null;
        //if($listDetails->order_status == '0')
        //{
        //   $statusData = 'Assigned Pharmacy';
        //}elseif($listDetails->order_status == '1')
        // {
        //   $statusData = 'Cancelled By Customer';
        //}elseif($listDetails->order_status == '2')
        // {
        //    $statusData = 'Order Confirmed';
        // }elseif($listDetails->order_status == '3')
        // {
        //     $statusData = 'Ready For Pickup';
        //}elseif($listDetails->order_status == '4')
        // {
        //    $statusData = 'Completed';
        // }

        $orderDeatilsList[$key]['id'] = isset($listDetails->id) ? $listDetails->id : "";
        $orderDeatilsList[$key]['status_id'] = isset($listDetails->order_status) ? $listDetails->order_status : "";
        $orderDeatilsList[$key]['status'] = isset($orderStatusData->name) ? $orderStatusData->name : "";
        $orderDeatilsList[$key]['order_id'] = isset($listDetails->order_id) ? $listDetails->order_id : "";
        $orderDeatilsList[$key]['bill_no'] = isset($listDetails->bill_no) ? $listDetails->bill_no : "";
        $orderDeatilsList[$key]['amount'] = isset($listDetails->new_amount) ? $listDetails->new_amount : "";
        $orderDeatilsList[$key]['date'] = isset($listDetails->created_at) ? date("d M Y H:i a", strtotime($listDetails->created_at)) : "";
      }
    }
    return $this->sendResponse($orderDeatilsList, 'Order Data Fatch Successfully.');
  }

  public function patientOrderDetails(Request $request)
  {
    $patientDatas = PatientsOrder::where('id', $request->order_id)->first();

    $dataList = [];
    if (isset($patientDatas)) {
      // $statusData = null;
      // if($patientDatas->order_status == '0')
      // {
      //     $statusData = 'Assigned Pharmacy';
      // } elseif($patientDatas->order_status == '1')
      // {
      //     $statusData = 'Cancelled By Customer';
      // } elseif($patientDatas->order_status == '2')
      // {
      //     $statusData = 'Order Confirmed';
      // } elseif($patientDatas->order_status == '3')
      // {
      //     $statusData = 'Ready For Pickup';
      // } elseif($patientDatas->order_status == '4')
      // {
      //     $statusData = 'Completed';
      // }

      $patientData = PatientsModel::where('id', $patientDatas->patient_id)->first();
      $patientAddress = PatientsAddress::where('id', $patientDatas->address_id)->first();
      $iteamList = IteamsModel::where('id', $patientDatas->iteam_id)->first();
      $orderStatusData = OrderStatusData::where('id', $patientDatas->order_status)->first();
      $patientFamilyData = PatientsFamilyModel::where('id',$patientDatas->famliy_member_id)->first();

      if ($patientDatas->delivery_status == '0') {
        $deliveryStatus = 'Pickup';
      } else {
        $deliveryStatus = 'Delivery';
      }

      $dataList['id'] = isset($patientDatas->id) ? $patientDatas->id : "";
      $dataList['order_id'] = isset($patientDatas->order_id) ? $patientDatas->order_id : "";
      $dataList['sale_id'] = isset($patientDatas->sale_id) ? $patientDatas->sale_id : "";
      $dataList['bill_no'] = isset($patientDatas->bill_no) ? $patientDatas->bill_no : "";
      $dataList['patient_name'] = isset($patientData->first_name) ? $patientData->first_name : "";
      $dataList['patient_number'] = isset($patientData->mobile_number) ? $patientData->mobile_number : "";
      $dataList['date'] = isset($patientDatas->created_at) ? date("d M Y H:i a", strtotime($patientDatas->created_at)) : "";
      $dataList['order_status_id'] = isset($patientDatas->order_status) ? $patientDatas->order_status : "";
      $dataList['order_status'] = isset($orderStatusData->name) ? $orderStatusData->name : "";
      $dataList['address'] = isset($patientAddress->address) ? $patientAddress->address : "";
      $dataList['famliy_member_id'] = isset($patientDatas->famliy_member_id) ? $patientDatas->famliy_member_id : "";
      $dataList['famliy_member_name'] = isset($patientFamilyData->first_name) ? $patientFamilyData->first_name : "";
      $dataList['area_landmark'] = isset($patientAddress->area_landmark) ? $patientAddress->area_landmark : "";
      $dataList['city'] = isset($patientAddress->city) ? $patientAddress->city : "";
      $dataList['pincode'] = isset($patientAddress->pincode) ? $patientAddress->pincode : "";
      $dataList['location'] = "-";
      $dataList['delivery_status'] = $deliveryStatus;
      // $dataList['iteam_name'] = isset($iteamList->iteam_name) ? $iteamList->iteam_name:"";
      // $dataList['old_unit'] = isset($iteamData->old_unit) ? $iteamData->old_unit :"";
      // $dataList['unit'] = isset($iteamData->unit) ? $iteamData->unit : "";
      // $dataList['qty'] = isset($patientDatas->qty) ? $patientDatas->qty : "";
      // $dataList['price'] = isset($patientDatas->price) ? $patientDatas->price : "";
      $dataList['packing_size'] = isset($iteamData->packing_size) ? $iteamData->packing_size : "";
      $dataList['front_photo'] = isset($iteamData->front_photo) ? asset('/public/front_photo/' . $iteamData->front_photo) : "";
      $dataList['payment_method'] = "Cash on Delivery";
      $dataList['round_off'] = isset($patientDatas->round_off) ? $patientDatas->round_off : "";
      $dataList['net_amount'] = isset($patientDatas->new_amount) ? $patientDatas->new_amount : "";
      $dataList['total_amount'] = isset($patientDatas->total_amount) ? $patientDatas->total_amount : "";

      $patientOrderItemData = PatientOrderItem::where('patient_order_id', $request->order_id)->get();
      $dataList['item_list'] = [];
      if (isset($patientOrderItemData)) {
        foreach ($patientOrderItemData as $data => $list) {
          $iteamList = IteamsModel::where('id', $list->iteam_id)->first();
          $uniteData = UniteTable::where('id', $iteamList->old_unit)->first();

          $dataList['item_list'][$data]['id'] = isset($list->id) ? $list->id : "";
          $dataList['item_list'][$data]['item_id'] = isset($list->iteam_id) ? $list->iteam_id : "";
          $dataList['item_list'][$data]['item_name'] = isset($iteamList->iteam_name) ? $iteamList->iteam_name : "";
          $dataList['item_list'][$data]['unit'] = isset($iteamList->unit) ? $iteamList->unit : "";
          $dataList['item_list'][$data]['price'] = isset($list->price) ? $list->price : "";
          $dataList['item_list'][$data]['qty'] = isset($list->qty) ? $list->qty : "";
          $dataList['item_list'][$data]['total'] = isset($list->sub_amount) ? $list->sub_amount : "";
          $dataList['item_list'][$data]['image'] = isset($iteamList->front_photo) ? asset('/public/front_photo/' . $iteamList->front_photo) : "";
        }
      }

      $prescrptionData = PatientOrderImages::where('patient_order_id', $request->order_id)->get();

      $dataList['prescrption_list'] = [];
      if (isset($prescrptionData)) {
        foreach ($prescrptionData as $keys => $listDetailsPrescrption) {
          $dataList['prescrption_list'][$keys]['id'] = isset($listDetailsPrescrption->id) ? $listDetailsPrescrption->id : "";
          $dataList['prescrption_list'][$keys]['image'] = isset($listDetailsPrescrption->image) ? asset('public/license_image/' . $listDetailsPrescrption->image) : "";
        }
      }
      
      return $this->sendResponse($dataList, 'Order Details Retrieved Successfully.');
    }else
    {
    	return $this->sendError('Order Data Not Found.');
    }
  }

  public function patientCancelOrder(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required',
    ], [
      'id.required' => 'Enter Id',
    ]);

    if ($validator->fails()) {
      $error = $validator->getMessageBag();
      return $this->sendError($error->first());
    }

    $patientDatas = PatientsOrder::where('id', $request->id)->first();
    if (isset($patientDatas)) {
      $patientDatas->order_status = '2';
      $patientDatas->update();
    } else {
      return $this->sendError('Order Data Not Found.');
    }

    return $this->sendResponse([], 'Your Order has been Cancel Successfully.');
  }

  public function chemistOrder(Request $request)
  {
    $patientOrders = PatientsOrder::where('chemist_id', auth()->user()->id);

    if (isset($request->order_status)) {
      	$patientOrders->where('order_status', $request->order_status);
    }
    if (isset($request->order_id)) {
      	$patientOrders->where('order_id', 'LIKE','%'.$request->order_id.'%');
    }
    if (isset($request->start_date) && isset($request->end_date)) {
      	$patientOrders->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }
    if (isset($request->patient_name)) {
      	$chemistData = PatientsModel::where('first_name', 'LIKE', '%' . $request->patient_name . '%')
          ->pluck('id')
          ->toArray();
      	$patientOrders->whereIn('patient_id', $chemistData);
    }
    $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
    $limit = $request->filled('limit') ? max(1, intval($request->limit)) : 10;

    $offset = ($page - 1) * $limit;

    $patientOrders = $patientOrders->limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
    
    $patientOrdersCount = PatientsOrder::where('chemist_id', auth()->user()->id)->count();

    $patientDetails = [];

    // Order Status Mapping
    $statusMap = [
      '0' => 'Assigned Pharmacy',
      '1' => 'Cancelled',
      '2' => 'Accepted',
      '3' => 'Ready For Pickup',
      '4' => 'Completed'
    ];

    foreach ($patientOrders as $key => $listDetails) {
      $chemistData = PatientsModel::find($listDetails->patient_id);
      $patientAddress = PatientsAddress::find($listDetails->address_id);
      $iteamList = IteamsModel::find($listDetails->iteam_id);
      $orderStatusData = OrderStatusData::where('id', $listDetails->order_status)->first();

      $patientOrderItemData = PatientOrderItem::where('patient_order_id', $listDetails->id)->get();

      $deliveryStatus = ($listDetails->delivery_status == '0') ? 'Pickup' : 'Delivery';

      $patientDetails[$key]['id'] = $listDetails->id ?? "";
      $patientDetails[$key]['order_id'] = $listDetails->order_id ?? "";
      $patientDetails[$key]['patient_name'] = $chemistData->first_name ?? "";
      $patientDetails[$key]['patient_number'] = $chemistData->mobile_number ?? "";
      $patientDetails[$key]['delivery_address'] = $patientAddress->address ?? "";
      $patientDetails[$key]['date'] = date("d-m-Y H:i a", strtotime($listDetails->created_at)) ?? "";
      $patientDetails[$key]['delivery_status'] = $deliveryStatus ?? "";
      $patientDetails[$key]['status_id'] = $listDetails->order_status ?? "";
      $patientDetails[$key]['status'] = isset($orderStatusData->name) ? $orderStatusData->name : "";
      $patientDetails[$key]['item_list'] = [];
      if (isset($patientOrderItemData)) {
        foreach ($patientOrderItemData as $data => $list) {
          $iteamList = IteamsModel::where('id', $list->iteam_id)->first();
          $uniteData = UniteTable::where('id', $iteamList->old_unit)->first();

          $patientDetails[$key]['item_list'][$data]['id'] = isset($list->id) ? $list->id : "";
          $patientDetails[$key]['item_list'][$data]['item_id'] = isset($list->iteam_id) ? $list->iteam_id : "";
          $patientDetails[$key]['item_list'][$data]['item_name'] = isset($iteamList->iteam_name) ? $iteamList->iteam_name : "";
          $patientDetails[$key]['item_list'][$data]['old_unit'] = isset($iteamList->old_unit) ? $iteamList->old_unit : "";
          $patientDetails[$key]['item_list'][$data]['unit'] = isset($iteamList->unit) ? $iteamList->unit : "";
          $patientDetails[$key]['item_list'][$data]['price'] = isset($list->price) ? $list->price : "";
          $patientDetails[$key]['item_list'][$data]['qty'] = isset($list->qty) ? $list->qty : "";
          $patientDetails[$key]['item_list'][$data]['total'] = isset($list->sub_amount) ? $list->sub_amount : "";
          $patientDetails[$key]['item_list'][$data]['image'] = isset($iteamList->front_photo) ? asset('/public/front_photo/' . $iteamList->front_photo) : "";
        }
      }
      // $patientDetails[$key]['iteam_name'] = $iteamList->iteam_name ?? "";
      // $patientDetails[$key]['old_unit'] = $iteamList->old_unit ?? "";
      // $patientDetails[$key]['unit'] = $iteamList->unit ?? "";
      // $patientDetails[$key]['qty'] = $listDetails->qty ?? "";
      // $patientDetails[$key]['price'] = $listDetails->price ?? "";
      // $patientDetails[$key]['packing_size'] = $iteamList->packing_size ?? "";
      $patientDetails[$key]['front_photo'] = isset($iteamList->front_photo) ? asset('/public/front_photo/' . $iteamList->front_photo) : "";
      $patientDetails[$key]['round_off'] = $listDetails->round_off ?? "";
      $patientDetails[$key]['net_amount'] = $listDetails->new_amount ?? "";
      $patientDetails[$key]['total_amount'] = $listDetails->total_amount ?? "";
    }
    
    $response = [
      'status' => 200,
      'count' => !empty($request->page) ? $patientOrders->count() : $patientOrdersCount,
      'total_records' => $patientOrdersCount,
      'data'   => $patientDetails,
      'message' => 'Data Fetch Successfully.',
    ];
    return response()->json($response, 200);

    // return $this->sendResponse($patientDetails, 'Data Fetch Successfully.');
  }

  public function patientOrderStatusList()
  {
    $order_status_data_list = OrderStatusData::get();

    $orderStatusDetails = [];
    if (isset($order_status_data_list)) {
      foreach ($order_status_data_list as $key => $list) {
        $orderStatusDetails[$key]['id'] = isset($list->id) ? $list->id : "";
        $orderStatusDetails[$key]['name'] = isset($list->name) ? $list->name : "";
      }
    }
    return $this->sendResponse($orderStatusDetails, 'Order Status Data Fetch Successfully.');
  }

  public function notificationList()
  {
    $notificationData = NotificationModel::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
    $notificationDataDetails = [];
    if (isset($notificationData)) {
      foreach ($notificationData as $key => $list) {
        $notificationDataDetails[$key]['id'] = isset($list->id) ? $list->id : "";
        $notificationDataDetails[$key]['order_id'] = isset($list->patient_order_id) ? $list->patient_order_id : "";
        $notificationDataDetails[$key]['item_id'] = isset($list->item_id) ? $list->item_id : "";
        $notificationDataDetails[$key]['title'] = isset($list->title) ? $list->title : "";
        $notificationDataDetails[$key]['date'] = isset($list->created_at) ? Carbon::parse($list->created_at)->format('d M Y g:i A') : "";
        $notificationDataDetails[$key]['description'] = isset($list->description) ? $list->description : "";
      }
    }
    
    return $this->sendResponse($notificationDataDetails, 'Notification Data Fetch Successfully.');
  }

  public function acceptRejectOrder(Request $request)
  {
    $patient_order_data = PatientsOrder::where('id', $request->order_id)->first();
    if (isset($patient_order_data)) {
      $orderId = $patient_order_data->order_id;
      $user_data = User::where('id', $patient_order_data->chemist_id)->first();
      if ($request->status == '4') {
        $patient_order_data->order_status = $request->status;
        $patient_order_data->reason = null;
        $patient_order_data->update();

        $notification_data = new NotificationModel();
        $notification_data->title = '✅ Order Accepted';
        $notification_data->description = 'Your order ' . $orderId . ' has been accepted by the ' . $user_data->name . '.';
        $notification_data->user_id = $patient_order_data->patient_id;
        $notification_data->order_id = $orderId;
        $notification_data->is_send = 2;
        $notification_data->save();
        
        $title = $notification_data->title;
        $message = $notification_data->description;
        $userId = $patient_order_data->patient_id;

        $this->patient_notification($title, $message, $userId);

        return $this->sendResponse([], 'Order Accepted Successfully.');
      } else {
        $patient_order_data->order_status = $request->status;
        $patient_order_data->reason = $request->reason;
        $patient_order_data->update();

        $notification_data = new NotificationModel();
        $notification_data->title = '❌ Order Rejected';
        $notification_data->description = 'Sorry, your order ' . $orderId . ' was rejected by the ' . $user_data->name . '. Please choose a different pharmacy to proceed.';
        $notification_data->user_id = $patient_order_data->patient_id;
        $notification_data->order_id = $orderId;
        $notification_data->is_send = 2;
        $notification_data->save();
        
        $title = $notification_data->title;
        $message = $notification_data->description;
        $userId = $patient_order_data->patient_id;

        $this->patient_notification($title, $message, $userId);

        return $this->sendResponse([], 'Order Rejected Successfully.');
      }
    } else {
      return $this->sendError('Order Data Not Found.');
    }
  }

  public function setRefillReminder(Request $request)
  {
    // dd($request->duration > 3,$request->duration - 3,date('d-m-Y'));die;
    // 2 days pela ni notification set kareli che.
    if ($request->duration > 3) {
      $date = Carbon::now();
      $date->addDays($request->duration - 3);
      $notification_date = $date->format('d-m-Y');
    } else {
      $notification_date = date('d-m-Y');
    }

    $patient_order_data = PatientsOrder::where('id', $request->order_id)->first();
    $item_data = IteamsModel::where('id', $request->item_id)->first();

    $refill_reminder_store_data = new SetRefillReminder();
    $refill_reminder_store_data->order_id = $request->order_id;
    $refill_reminder_store_data->item_id = $request->item_id;
    $refill_reminder_store_data->duration = $request->duration;
    $refill_reminder_store_data->morning = $request->morning;
    $refill_reminder_store_data->afternoon = $request->afternoon;
    $refill_reminder_store_data->night = $request->night;
    $refill_reminder_store_data->user_id = auth()->user()->id;
    $refill_reminder_store_data->notification_date = $notification_date;
    $refill_reminder_store_data->save();

    if ($notification_date == date('d-m-Y')) {
      $notification_data = new NotificationModel();
      $notification_data->title = 'Refill Reminder';
      $notification_data->description = '' . $item_data->iteam_name . ' will run out soon. Refill now! Time to reorder ' . $item_data->iteam_name . '.';
      $notification_data->user_id = auth()->user()->id;
      $notification_data->order_id = $patient_order_data->order_id;
      $notification_data->date = $notification_date;
      $notification_data->patient_order_id = $request->order_id;
      $notification_data->save();
    }

    return $this->sendResponse([], 'Refill Reminder Set Successfully.');
  }

  public function RefillReminderList(Request $request)
  {
    $search = $request->input('search');

    $refill_reminder_list = SetRefillReminder::with('itemNameGet')
      ->when($search, function ($query, $search) {
        $query->whereHas('itemNameGet', function ($q) use ($search) {
          $q->where('iteam_name', 'LIKE', '%' . $search . '%'); // assuming 'name' is the field for item name
        });
      })
      ->where('user_id', auth()->user()->id)->get();

    $refillReminderListDetails = [];
    if (isset($refill_reminder_list)) {
      foreach ($refill_reminder_list as $key => $list) {
        $itemData = IteamsModel::where('id', $list->item_id)->first();

        $refillReminderListDetails[$key]['id'] = isset($list->id) ? $list->id : '';
        $refillReminderListDetails[$key]['item_name'] = isset($itemData->iteam_name) ? $itemData->iteam_name : '';
        $refillReminderListDetails[$key]['image'] = isset($itemData->front_photo) ? asset('/public/front_photo/' . $itemData->front_photo) : "";
        $refillReminderListDetails[$key]['duration'] = isset($list->duration) ? $list->duration : '';
        $refillReminderListDetails[$key]['morning'] = isset($list->morning) ? $list->morning : '';
        $refillReminderListDetails[$key]['afternoon'] = isset($list->afternoon) ? $list->afternoon : '';
        $refillReminderListDetails[$key]['night'] = isset($list->night) ? $list->night : '';
      }
    }

    return $this->sendResponse($refillReminderListDetails, 'Refill Reminder List Fetch Successfully.');
  }

  public function RefillReminderEdit(Request $request)
  {
    if ($request->duration > 3) {
      $date = Carbon::now();
      $date->addDays($request->duration - 3);
      $notification_date = $date->format('d-m-Y');
    } else {
      $notification_date = date('d-m-Y');
    }

    $refill_reminder_edit_data = SetRefillReminder::where('id', $request->id)->first();
    $patient_order_data = PatientsOrder::where('id', $refill_reminder_edit_data->order_id)->first();
    $item_data = IteamsModel::where('id', $refill_reminder_edit_data->item_id)->first();

    if (isset($refill_reminder_edit_data)) {
      $refill_reminder_edit_data->duration = $request->duration;
      $refill_reminder_edit_data->morning = $request->morning;
      $refill_reminder_edit_data->afternoon = $request->afternoon;
      $refill_reminder_edit_data->night = $request->night;
      $refill_reminder_edit_data->user_id = auth()->user()->id;
      $refill_reminder_edit_data->notification_date = $notification_date;
      $refill_reminder_edit_data->update();

      if ($notification_date == date('d-m-Y')) {
        $notification_data = new NotificationModel();
        $notification_data->title = 'Refill Reminder';
        $notification_data->description = '' . $item_data->iteam_name . ' will run out soon. Refill now! Time to reorder ' . $item_data->iteam_name . '.';
        $notification_data->user_id = auth()->user()->id;
        $notification_data->order_id = $patient_order_data->order_id;
        $notification_data->date = $notification_date;
        $notification_data->patient_order_id = $refill_reminder_edit_data->order_id;
        $notification_data->save();
      }

      return $this->sendResponse([], 'Refill Reminder Updated Successfully.');
    } else {
      return $this->sendError('Refill Reminder Data Not Found.');
    }
  }

  public function RefillReminderDelete(Request $request)
  {
    $refill_reminder_delete_data = SetRefillReminder::where('id', $request->id)->first();

    if (isset($refill_reminder_delete_data)) {
      $refill_reminder_delete_data->delete();

      return $this->sendResponse([], 'Refill Reminder Deleted Successfully.');
    } else {
      return $this->sendError('Refill Reminder Data Not Found.');
    }
  }

  public function setPillReminder(Request $request)
  {
    $time = Carbon::createFromFormat('h:i A', $request->time);

    // Subtract 4 minutes
    // 5 minute pela ni notification set kareli che.
    $updatedTime = $time->subMinutes(5);

    // Output the result in 'h:i A' format
    $notificationTime = $updatedTime->format('h:i A');

    $pill_reminder_store_data = new SetPillReminder();
    $pill_reminder_store_data->order_id = $request->order_id;
    $pill_reminder_store_data->item_id = $request->item_id;
    $pill_reminder_store_data->reminder_type = $request->reminder_type;
    $pill_reminder_store_data->time = $request->time;
    $pill_reminder_store_data->user_id = auth()->user()->id;
    $pill_reminder_store_data->notification_time = $notificationTime;
    $pill_reminder_store_data->save();

    $patient_order_data = PatientsOrder::where('id', $request->order_id)->first();
    $item_data = IteamsModel::where('id', $request->item_id)->first();

    if (isset($request->days)) {
      foreach (json_decode($request->days) as $list) {
        $pill_reminder_days_store_data = new SetPillReminderDays();
        $pill_reminder_days_store_data->pill_reminder_id = $pill_reminder_store_data->id;
        $pill_reminder_days_store_data->days = $list;
        $pill_reminder_days_store_data->user_id = auth()->user()->id;
        $pill_reminder_days_store_data->save();
      }
    }

    if (Carbon::now()->format('h:i A') == $notificationTime) {
      $notification_data = new NotificationModel();
      $notification_data->title = 'Pill Reminder';
      $notification_data->description = 'Its time to take your medicine: ' . $item_data->iteam_name . ' Reminder: Take ' . $item_data->iteam_name . ' now';
      $notification_data->user_id = auth()->user()->id;
      $notification_data->order_id = $patient_order_data->order_id;
      $notification_data->time = $notificationTime;
      $notification_data->item_id = $request->item_id;
      $notification_data->save();

      $title = $notification_data->title;
      $message = $notification_data->description;
      $userId = auth()->user()->id;

      $this->post_notification($title, $message, $userId);
    }

    return $this->sendResponse([], 'Pill Reminder Set Successfully.');
  }

  public function pillReminderList(Request $request)
  {
    // dd(Carbon::now()->subMinute(4)->format('h:i A'),Carbon::now()->format('h:i A'));
    $search = $request->input('search');

    $pillReminderList = SetPillReminder::with('itemNameGet')
      ->when($search, function ($query, $search) {
        $query->whereHas('itemNameGet', function ($q) use ($search) {
          $q->where('iteam_name', 'LIKE', '%' . $search . '%'); // assuming 'name' is the field for item name
        });
      })
      ->where('user_id', auth()->user()->id)->get();

    $pillReminderListDetails = [];
    if (isset($pillReminderList)) {
      foreach ($pillReminderList as $key => $list) {
        $pillReminderDaysList = SetPillReminderDays::where('pill_reminder_id', $list->id)->get();
        $itemData = IteamsModel::where('id', $list->item_id)->first();

        if ($list->type == 1) {
          $pauseResumeType = true;
        } else {
          $pauseResumeType = false;
        }

        $pillReminderListDetails[$key]['id'] = isset($list->id) ? $list->id : '';
        $pillReminderListDetails[$key]['item_name'] = isset($itemData->iteam_name) ? $itemData->iteam_name : '';
        $pillReminderListDetails[$key]['image'] = isset($itemData->front_photo) ? asset('/public/front_photo/' . $itemData->front_photo) : "";
        $pillReminderListDetails[$key]['reminder_type'] = isset($list->reminder_type) ? $list->reminder_type : '';
        $pillReminderListDetails[$key]['pause_resume_type'] = isset($pauseResumeType) ? $pauseResumeType : '';
        $pillReminderListDetails[$key]['days'] = [];
        if (isset($pillReminderDaysList)) {
          foreach ($pillReminderDaysList as $day) {
            $pillReminderListDetails[$key]['days'][] = $day->days;
          }
        }

        $pillReminderListDetails[$key]['time'] = isset($list->time) ? $list->time : '';
      }
    }

    return $this->sendResponse($pillReminderListDetails, 'Pill Reminder Data Fetch Successfully.');
  }

  public function pillReminderEdit(Request $request)
  {
    $time = Carbon::createFromFormat('h:i A', $request->time);

    // Subtract 4 minutes
    $updatedTime = $time->subMinutes(5);

    // Output the result in 'h:i A' format
    $notificationTime = $updatedTime->format('h:i A');

    $pill_reminder_edit_data = SetPillReminder::where('id', $request->id)->first();
    $pill_reminder_edit_data->reminder_type = $request->reminder_type;
    $pill_reminder_edit_data->time = $request->time;
    $pill_reminder_edit_data->user_id = auth()->user()->id;
    $pill_reminder_edit_data->notification_time = $notificationTime;
    $pill_reminder_edit_data->update();

    $patient_order_data = PatientsOrder::where('id', $pill_reminder_edit_data->order_id)->first();
    $item_data = IteamsModel::where('id', $pill_reminder_edit_data->item_id)->first();

    if (isset($request->days)) {
      $pill_reminder_days_data = SetPillReminderDays::where('pill_reminder_id', $request->id)->delete();

      foreach (json_decode($request->days) as $list) {
        $pill_reminder_days_store_data = new SetPillReminderDays();
        $pill_reminder_days_store_data->pill_reminder_id = $pill_reminder_edit_data->id;
        $pill_reminder_days_store_data->days = $list;
        $pill_reminder_days_store_data->user_id = auth()->user()->id;
        $pill_reminder_days_store_data->save();
      }
    }

    if (Carbon::now()->format('h:i A') == $notificationTime) {
      $notification_data = new NotificationModel();
      $notification_data->title = 'Pill Reminder';
      $notification_data->description = 'Its time to take your medicine: ' . $item_data->iteam_name . ' Reminder: Take ' . $item_data->iteam_name . ' now';
      $notification_data->user_id = auth()->user()->id;
      $notification_data->order_id = $patient_order_data->order_id;
      $notification_data->time = $notificationTime;
      $notification_data->item_id = $pill_reminder_edit_data->item_id;
      $notification_data->save();
    }

    return $this->sendResponse([], 'Pill Reminder Updated Successfully.');
  }

  public function pillReminderDelete(Request $request)
  {
      $pill_reminder_delete_data = SetPillReminder::where('id', $request->id)->first();

      if (isset($pill_reminder_delete_data)) {
        $pill_reminder_days_data = SetPillReminderDays::where('pill_reminder_id', $request->id)->get();
        if (isset($pill_reminder_days_data)) {
            foreach ($pill_reminder_days_data as $list) {
              $list->delete();
            }
        }

        $pill_reminder_delete_data->delete();

        return $this->sendResponse([], 'Pill Reminder Deleted Successfully.');
      } else {
        return $this->sendError('Pill Reminder Data Not Found.');
      }
  }

  public function pillReminderPauseResume(Request $request)
  {
      $pillReminderData = SetPillReminder::where('id', $request->id)->first();
      if (isset($pillReminderData)) {
        $pillReminderData->type = $request->type;
        $pillReminderData->update();

        if ($request->type == 1) {
          	return $this->sendResponse([], 'Pill Reminder Resumed Successfully.');
        } else {
          	return $this->sendResponse([], 'Pill Reminder Paused Successfully.');
        }
      } else {
        return $this->sendError('Pill Reminder Data Not Found.');
      }
  }
  
  public function checkChemistNotificationSend()
  {
    	$title = "Title";
        $message = "📢 Notification Test Message ✅

        🔔 This is a test notification to check the proper working of our system.
        📋 The patient order system helps manage and track patient orders efficiently.
        👨‍⚕️ Each order contains important details such as patient information, prescribed medicines, quantity, and order status.
        🕒 Orders can have statuses like Pending ⏳, Accepted ✅, or Rejected ❌.
        📲 Notifications are triggered when the status of an order changes, keeping the chemist and patient updated in real time.
        ⚠️ This test ensures that alerts appear without delay and the data is displayed correctly.
        💡 Make sure that mobile notifications, sounds, and badges work properly.
        ✔️ Test completed successfully if you can see this message with icons on your device.
        📊 Monitoring patient orders improves accuracy and reduces mistakes in medicine delivery.
        💬 End of test message.";
        // $userId = auth()->user()->id;
        $userId = "269";
    
  		$this->chemist_notification($title, $message, $userId);
    
    	return $this->sendResponse([], 'Chemist Notification Sent Successfully.');
  }
  
  public function checkPatientNotificationSend()
  {
    	$title = "Title";
        $message = "📢 Notification Test Message ✅

        🔔 This is a test notification to check the proper working of our system.
        📋 The patient order system helps manage and track patient orders efficiently.
        👨‍⚕️ Each order contains important details such as patient information, prescribed medicines, quantity, and order status.
        🕒 Orders can have statuses like Pending ⏳, Accepted ✅, or Rejected ❌.
        📲 Notifications are triggered when the status of an order changes, keeping the chemist and patient updated in real time.
        ⚠️ This test ensures that alerts appear without delay and the data is displayed correctly.
        💡 Make sure that mobile notifications, sounds, and badges work properly.
        ✔️ Test completed successfully if you can see this message with icons on your device.
        📊 Monitoring patient orders improves accuracy and reduces mistakes in medicine delivery.
        💬 End of test message.";
        // $userId = auth()->user()->id;
        $userId = "21";
    
  		$this->patient_notification($title, $message, $userId);
    
    	return $this->sendResponse([], 'Patient Notification Sent Successfully.');
  }
}
