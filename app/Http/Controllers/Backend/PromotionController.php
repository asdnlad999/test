<?php

namespace App\Http\Controllers\Backend;

use App\Models\Domain;
use App\Models\Promotion;
use App\Models\PromotionPage;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::latest()->paginate();
        return view('promotion.index',compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::latest()->get();
        return view('promotion.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token','music','bg_img_file']);
        $input['uuid'] = Uuid::uuid1()->toString();
        $ret = Promotion::create($input);
        if ($ret){
            return redirect('/backend/promotion')->with('success','新增成功');
        }
        return redirect()->back()->withErrors("新增失败,请重试");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        $ret = $promotion->delete();
        $rst = PromotionPage::where('promotion_id',$promotion->id)->delete();
        if (!$ret){
            return response()->json(['status'=>1,'msg'=>'删除失败,请重试']);
        }
        return response()->json(['status'=>0,'msg'=>"删除推广页成功",'data'=>['id'=>$promotion->id]]);
    }

    public function showUrl(Request $request){
        $uuid = $request->get('uuid','');
        $domain = Domain::where('type',1)->where('is_use',1)->inRandomOrder()->first();
        if (!$domain){
            return response()->json(['status'=>0,'msg'=>'success','data'=>['url'=>'']]);
        }
        $one_dir = $this->generate_str(7);
        $two_dir = $this->generate_str(7);
        $three_dir = $this->generate_str(9);
//        $dir = "{$one_dir}/{$two_dir}/{$three_dir}";
        $dir = "PdiNxK5/{$two_dir}/{$three_dir}";
        $key = $this->generate_str(8);
        $param = '?'.$key.'='.$uuid.'='.'.png';
        $suiji = strtolower($this->generate_str(5));
//        $url = "http://{$suiji}.{$domain->domain_addr}/{$dir}{$param}";
        $url = "{$domain->domain_addr}{$dir}{$param}";
        $url = $this->shortUrl($url);
        if (!$url) {
            $url = '';
        }else {
            $url = $url[0]['url_short'];
        }
        return response()->json(['status'=>0,'msg'=>'success','data'=>['url'=>$url]]);
    }
    public function shortUrl($long_url){
        $api = 'http://api.t.sina.com.cn/short_url/shorten.json'; // json
        $source = '474694889';
        $request_url = sprintf($api.'?source=%s&url_long=%s', $source, $long_url);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_URL,$request_url);
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)){
            return false;
        }
        curl_close($ch);
        return json_decode($data,true);
    }
    public function generate_str($len = 16){
        $str = bcrypt(Str::random(16));
        $str = str_replace(['.','/','$'],'',$str);
        $str_len = strlen($str);
        $str = substr($str,mt_rand(0,round($str_len / 2)),$len);
        return $str;
    }

    public function pageIndex($promotion_id){
        $pages = PromotionPage::where('promotion_id',$promotion_id)->get();
        return view('promotion.page',compact('pages','promotion_id'));
    }

    public function pageCreate($promotion_id){
        return view('promotion.page_create',compact('promotion_id'));
    }

    public function pageStore(Request $request){
        $input = $request->except(['bg_img_file','_token']);
        $insert_data['promotion_id'] = $input['promotion_id'];
        $insert_data['padding'] = $input['padding'];
        $insert_data['background_image'] = $input['background_image'];
        $insert_data['background_color'] = $input['background_color'];
        foreach ($input['src'] as $key=>$value){
            $insert_data['pictures'][] = [
                'src'=>$value,
                'href'=>isset($input['href'][$key]) ? $input['href'][$key] : '',
                'width'=>$input['width'][$key],
                'height'=>$input['height'][$key],
                'top'=>$input['top'][$key],
                'left'=>$input['left'][$key],
                'animation'=>$input['animation'][$key],
                'delay'=>$input['delay'][$key],
            ];
        }
        $ret = PromotionPage::create($insert_data);
        if ($ret){
            return redirect('/backend/promotion/pages/'.$insert_data['promotion_id'])->with('success','添加成功');
        }
        return redirect()->back()->withErrors('添加页面失败，请重试');
    }
    public function pageEdit($id){
        $page = PromotionPage::find($id);
        return view('promotion.page_edit',compact('page'));
    }
    public function pageUpdate($id,Request $request){
        $input = $request->except(['bg_img_file','_token']);
        $insert_data['padding'] = $input['padding'];
        $insert_data['promotion_id'] = $input['promotion_id'];
        $insert_data['background_image'] = $input['background_image'];
        $insert_data['background_color'] = $input['background_color'];
        foreach ($input['src'] as $key=>$value){
            $insert_data['pictures'][] = [
                'src'=>$value,
                'href'=>isset($input['href'][$key]) ? $input['href'][$key] : '',
                'width'=>$input['width'][$key],
                'height'=>$input['height'][$key],
                'top'=>$input['top'][$key],
                'left'=>$input['left'][$key],
                'animation'=>$input['animation'][$key],
                'delay'=>$input['delay'][$key],
            ];
        }
        $insert_data['pictures'] = json_encode($insert_data['pictures']);
        $res = PromotionPage::where('id',$id)->update($insert_data);
        if ($res){
            return redirect('/backend/promotion/pages/'.$insert_data['promotion_id'])->with('success','编辑成功');
        }
        return redirect()->back()->withErrors('编辑页面失败，请重试');
    }
    public function getImgs($id = 0){
        $data = PromotionPage::where('id',$id)->select('pictures')->first();
        if (!$data){
            return response()->json(['status'=>1,'msg'=>'fail','data'=>[]]);
        }
        return response()->json(['status'=>0,'msg'=>'success','data'=>$data]);
    }

    public function deletePage($id){
        $ret = PromotionPage::where('id',$id)->delete();
        if (!$ret){
            return response()->json(['status'=>1,'msg'=>'删除失败,请重试']);
        }
        return response()->json(['status'=>0,'msg'=>"删除页面成功",'data'=>['id'=>$id]]);
    }

    public function edit(Promotion $promotion){
        return view('promotion.edit',compact("promotion"));
    }

    public function update(Promotion $promotion,Request $request){
        $input = $request->only(['music_url','bg_img','share_title','bg_color']);
        $rst = $promotion->update($input);
        if ($rst){
            return redirect('/backend/promotion')->with('success','编辑成功');
        }
        return redirect()->back()->withErrors("编辑失败,请重试");
    }
}
