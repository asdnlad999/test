<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword','');
        $userModel = User::latest();
        if ($keyword){
            $userModel->where('name','like',"%$keyword%");
        }
        $users = $userModel->paginate(10);
        return view('user.index',compact('users','keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy('level','asc')->get();
        return view('user.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'password'=>'required'
        ],[
            'name.required'=>'用户名必须填写',
            'password'=>'密码必须填写'
        ]);
        if ($request->get('email')){
            $has_email = User::where('email',$request->get('email'))->first();
            if ($has_email){
                return redirect()->back()->withErrors('邮箱已存在,换一个试试吧');
            }
        }
        if ($request->get('phone')){
            $has_email = User::where('phone',$request->get('phone'))->first();
            if ($has_email){
                return redirect()->back()->withErrors('手机号已存在,换一个试试吧');
            }
        }
        $input = $request->except(['_token']);
        $input['avatar'] = '/assets/images/avatar.jpeg';
        if ($input['pid'] !=0 ){
            $p_level = (int) User::where('id',$input['pid'])->value('level');
            $input['level'] = $p_level + 1;
        }else{
            $input['level'] = 0;
        }
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        if ($user){
            return redirect('/backend/user')->with('success','添加用户'.$user->name.'成功');
        }
        return redirect()->back()->withErrors('添加用户失败,请重试');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $users = User::orderBy('level','asc')->get();
        return view('user.edit',compact('user','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->except(['_token','_method']);
        $ret = $user->update($input);
        if ($ret){
            return redirect('/backend/user')->with('success','编辑用户'.$user->name.'成功');
        }
        return redirect()->back()->withErrors('编辑用户失败,请重试');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $ret = $user->delete();
        if (!$ret){
            return response()->json(['status'=>0,'msg'=>'删除失败,请重试']);
        }
        return response()->json(['status'=>1,'msg'=>"删除用户{$user->name}成功",'data'=>['id'=>$user->id]]);
    }
}
