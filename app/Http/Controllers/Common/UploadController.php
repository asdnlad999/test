<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class UploadController extends Controller
{
    public function uploadImg(Request $request)
    {
        $files = $request->allFiles();
        $data = [];
        foreach ($files as $file){
            if ($file->isValid()){
                $ext = $file->getClientOriginalExtension();
                $file_name = md5(Uuid::uuid1()->toString().time()).".{$ext}";
                $path = $file->storeAs('images',$file_name,'uploads');
                $data['path'] = env('STATIC_URL').'/uploads/'.$path;
            } else {
                return response()->json(['errno'=>1,'data'=>[]]);
            }
        }
        return response()->json(['errno'=>0,'data'=>$data]);
    }

    public function uploadMusic(Request $request){
        $file = $request->file('music_file');
        if (!$file->isValid()){
            return response()->json(['status'=>1,'msg'=>'无效的音乐文件','data'=>[]]);
        }
        $alow_ext = ['mp3','wma','rm','wav','flac','ape','midi'];
        $ext = $file->getClientOriginalExtension();
        if (!in_array(strtolower($ext),$alow_ext)){
            return response()->json(['status'=>1,'msg'=>'非法音频文件类型','data'=>[]]);
        }
        $file_name = md5(Uuid::uuid1()->toString().time()).".{$ext}";
        $path = $file->storeAs('music',$file_name,'uploads');
        $data['path'] = env('STATIC_URL').'/uploads/'.$path;
        return response()->json(['status'=>0,'msg'=>'文件上传成功','data'=>$data]);
    }

    public function uploadImages(Request $request){
        $file = $request->file('imgs');
        if ($file->isValid()){
            $alow_ext = ['png','jpg','jpeg','gif','webp'];
            $ext = $file->getClientOriginalExtension();
            if (!in_array(strtolower($ext),$alow_ext)){
                return response()->json(['status'=>1,'msg'=>'请不要上传非法图片哦','data'=>[]]);
            }
            $file_name = md5(Uuid::uuid1()->toString().time()).".{$ext}";
            $path = $file->storeAs('images',$file_name,'uploads');
            $data['path'] = env('STATIC_URL').'/uploads/'.$path;
            return response()->json(['status'=>0,'msg'=>'文件上传成功','data'=>$data]);
        }
        return response()->json(['status'=>1,'msg'=>'无效的图片文件','data'=>[]]);
    }
}
