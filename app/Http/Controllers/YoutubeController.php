<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Youtube;
use App\Http\Controllers\Api\ResponseController;

class YoutubeController extends ResponseController
{
    public function youtubeList(Request $request)
    {
       try{
             $youtubeList = Youtube::get();
             return view('admin.youtube.index',compact('youtubeList'));
         
         } catch (\Exception $e) {
            Log::info("Youtube List api" . $e->getMessage());
            return $e->getMessage();
        }
      
    }
  
  public function youtubeCreate(Request $request)
  {
     return view('admin.youtube.create'); 
  }
  
  public function youtubesStore(Request $request)
  {
        $youtubeStore = new Youtube;
        $youtubeStore->name = $request->name;
        $youtubeStore->youtube_link = $request->youtube_link;
        $youtubeStore->save();    
    
       return redirect()->route('youtue-list')->with('success', 'Youtube Link Added Successfully');
  }
  
  public function youtubeDelete($id)
  {
     $youtubeDelete = Youtube::find($id);
    if(isset($youtubeDelete))
    {
      $youtubeDelete->delete();
    }
     return redirect()->route('youtue-list')->with('success', 'Youtube Link Deleted Successfully');
  }
  
  public function editCreate($id)
  {
    $youtubeEdit = Youtube::find($id);
    return view('admin.youtube.edit',compact('youtubeEdit'));
  }
  
  public function youtubeUpdate(Request $request)
  {
     $youtubeEdit = Youtube::where('id',$request->id)->first();
      if(isset($youtubeEdit))
      {
          $youtubeEdit->name = $request->name;
          $youtubeEdit->youtube_link = $request->youtube_link;
          $youtubeEdit->update();
      }
     return redirect()->route('youtue-list')->with('success', 'Youtube Link Updated Successfully');
  }
  
  public function youtubeGet(Request $request)
  {
     $youtubeEdit = Youtube::get();
     $listData['data'] = [];
     if(isset($youtubeEdit))
     {
        foreach($youtubeEdit as $key => $list)
        {
           $url = $list->youtube_link;
      
          // Extract the video ID from the YouTube URL
          $videoId = '';
          $parts = parse_url($url);
          if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
            if (isset($query['v'])) {
              $videoId = $query['v'];
            }
          }
            $listData['data'][$key]['id'] = isset($list->id) ? $list->id :"";
            $listData['data'][$key]['name'] = isset($list->name) ? $list->name :"";
            $listData['data'][$key]['youtube_link'] = isset($list->youtube_link) ? $list->youtube_link :"";
            $listData['data'][$key]['video_code'] = isset($videoId) ? $videoId :"";
            $listData['data'][$key]['thumbnail'] = 'https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg';
        }
     }
     $listData['whatsapp_number'] =  "https://api.whatsapp.com/send?phone=https://wa.me/+919979194745&text=hi";
     $listData['phone_number'] = '1234567891';
    return $this->sendResponse($listData, 'Data Fetch Successfully');
  }
}
