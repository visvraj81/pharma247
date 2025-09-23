<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController;
use App\Models\ItemCategory;

class ItemCategoryController extends ResponseController
{
    //this function use create item category
    public function createItemcategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required',
            ], [
                'category_name.required' => 'Enter Category Name'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $item_category = new ItemCategory;
            $item_category->category_name = $request->category_name;
            $item_category->save();

            return $this->sendResponse('', 'Item Category Added Successfully');
        } catch (\Exception $e) {
            Log::info("Create Item Category api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use list item category
    public function listItemcategory(Request $request)
    {
        try {

            $itemCategoryList = ItemCategory::orderBy('id', 'DESC');
             $limit = 10;
                $page = $request->filled('page') ? max(1, intval($request->page)) : 1;
                $offset = ($page - 1) * $limit;
            $itemCategoryList = $itemCategoryList->offset($offset)->limit($limit)->get();
            

            $itemCategoryListArray = [];
            if (isset($itemCategoryList)) {
                foreach ($itemCategoryList as $key => $value) {
                    $itemCategoryListArray[$key]['id'] = isset($value->id) ? $value->id : '';
                     $itemCategoryListArray[$key]['count'] = count($itemCategoryList);
                    $itemCategoryListArray[$key]['category_name'] = isset($value->category_name) ? $value->category_name : '';
                }
            }
            return $this->sendResponse($itemCategoryListArray, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("List Item Category api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use edit item category
    public function editItemcategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ], [
                'id.required' => "Enter Item Category Id",
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $itemCategoryEdit = ItemCategory::where('id', $request->id)->first();

            if (empty($itemCategoryEdit)) {
                return $this->sendError('Data Not Found');
            }

            $itemCategoryEditData = [];
            $itemCategoryEditData['id'] = isset($itemCategoryEdit->id) ? $itemCategoryEdit->id : '';
            $itemCategoryEditData['category_name'] = isset($itemCategoryEdit->category_name) ? $itemCategoryEdit->category_name : '';

            return $this->sendResponse($itemCategoryEditData, 'Data Fetch Successfully');
        } catch (\Exception $e) {
            Log::info("Edit Item Category api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use update item category
    public function updateItemcategory(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'category_name' => 'required',
            ], [
                'id.required' => 'Enter Item Category Id',
                'category_name.required' => 'Enter Category Name'
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $itemCategoryUpdate = ItemCategory::find($request->id);
            $itemCategoryUpdate->category_name = $request->category_name;
            $itemCategoryUpdate->update();

            return $this->sendResponse('', 'Item Category Updated Successfully');
        } catch (\Exception $e) {
            Log::info("Update Item Category api" . $e->getMessage());
            return $e->getMessage();
        }
    }

    //this function use delete item category
    public function deleteItemcategory(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Enter Item Category Id',
            ]);

            if ($validator->fails()) {
                $error = $validator->getMessageBag();
                return $this->sendError($error->first());
            }

            $itemCategoryDelete = ItemCategory::where('id', $request->id)->first();
            if(isset($itemCategoryDelete))
            {
                $itemCategoryDelete->delete();
            }
            return $this->sendResponse('', 'Item Category Deleted Successfully');
        } catch (\Exception $e) {
            Log::info("Delete Item Category api" . $e->getMessage());
            return $e->getMessage();
        }
    }
}
