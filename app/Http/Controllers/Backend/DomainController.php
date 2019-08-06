<?php

namespace App\Http\Controllers\Backend;

use App\Models\Domain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::latest()->paginate(8);
        return view('domain.index',compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('domain.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token']);
        $data = Uuid::uuid1();
        $uuid = $data->toString();
        $input['uuid'] = $uuid;
        $domain = Domain::create($input);
        if ($domain){
            return redirect('/backend/domain')->with('success','添加域名成功');
        }
        return redirect()->back()->withErrors('添加域名失败,请重试');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        return view('domain.edit',compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        $input = $request->except(['_method','_token']);
        if ($domain->update($input)){
            return redirect('/backend/domain')->with('success','编辑域名成功');
        }
        return redirect()->back()->withErrors('编辑域名失败,请重试');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        if (!$domain->delete()){
            return response()->json(['status'=>1,'msg'=>'删除失败,请重试']);
        }
        return response()->json(['status'=>0,'msg'=>"删除域名成功",'data'=>['id'=>$domain->id]]);

    }

    public function changeStatus(Domain $domain)
    {
        $ret = $domain->update(['is_use'=>!$domain->is_use]);
        if ($ret){
            return response()->json(['status'=>0,'is_use'=>(int) $domain->is_use]);
        }
        return response()->json(['status'=>1]);
    }
}
