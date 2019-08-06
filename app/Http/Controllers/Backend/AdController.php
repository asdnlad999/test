<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->is_admin){
            $ads = Ad::latest()->paginate(8);
        }else{
            $ads = Ad::where('user_id',Auth::id())->latest()->paginate(8);
        }
        return view('ad.index',compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ad.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Ad::where('user_id',Auth::id())->first()){
            return redirect()->back()->withErrors('每个用户仅能添加一条广告链接');
        }
        $input = $request->except(['_token']);
        $input['uuid'] = Uuid::uuid1()->toString();
        $input['user_id'] = Auth::id();
        $ret = Ad::create($input);
        if ($ret){
            return redirect('/backend/ad')->with('success','广告添加成功');
        }
        return redirect()->back()->withErrors('广告添加失败');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ad $ad)
    {
        return view('ad.edit',compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ad $ad)
    {
        $input = $request->except(['_token','_method']);
        $ret = $ad->update($input);
        if ($ret){
            return redirect('/backend/ad')->with('success','广告修改成功');
        }
        return redirect()->back()->withErrors('广告修改失败');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        if (!$ad->delete()){
            return response()->json(['status'=>1,'msg'=>'删除失败,请重试']);
        }
        return response()->json(['status'=>0,'msg'=>"删除广告成功",'data'=>['id'=>$ad->id]]);
    }
}
