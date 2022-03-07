<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;




class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    
    {
        $user=Auth::user();

        //メモ一覧を取得
        $memos = Memo::where('user_id',$user['id'])->where('status',1)->orderBy('updated_at','DESC')->get();
        return view('create',compact('user','memos'));
    }

    public function create()
    //新規作成
    {
        //ログインしているユーザー情報を渡す
        $user=Auth::user();
        $memos = Memo::where('user_id',$user['id'])->where('status',1)->orderBy('updated_at','DESC')->get();
        return view('create',compact('user','memos'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // POSTされたデータをmemosテーブルに挿入

        // 同じタグがあるか確認
        $exist_tag = Tag::where('name', $data['tag'])->where('user_id', $data['user_id'])->first();
        if( empty($exist_tag['id']) ){
            //先にタグをDBにインサート
            $tag_id = Tag::insertGetId(['name' => $data['tag'], 'user_id' => $data['user_id']]);
        }else{
            $tag_id = $exist_tag['id'];
        }

        //リマインダーをDBにインサート
        
        //メモをDBニインサート
        $memo_id = Memo::insertGetId([
            'content' => $data['content'],
            'user_id' => $data['user_id'], 
            'email'=> $data['email'],
            'send_at'=> $data['send_at'],
            'tag_id' => $tag_id,
            'status' => 1



        ]);
        
        // リダイレクト処理
        return redirect()->route('home');
    }
    
    public function edit($id)
    //編集機能
    {
        // 該当するIDのメモをデータベースから取得
        $user = Auth::user();
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])
        ->first();
        
        $memos = Memo::where('user_id',$user['id'])->where('status',1)->orderBy('updated_at','DESC')->get();
        //取得したメモをViewに渡す
        $tags = Tag::where('user_id',$user['id'])->get();
        return view('edit',compact('memo','user','memos','tags'));
    }
    
    public function update(Request $request, $id)
    //更新機能
    {
        $inputs = $request->all();
        
        Memo::where('id', $id)->update(['content' => $inputs['content'],'tag_id' => $inputs['tag_id']]);
        return redirect()->route('home');
        }
    public function delete(Request $request, $id)
    //削除機能
    {
        $inputs = $request->all();
        //status2で論理消去
        Memo::where('id', $id)->update([ 'status' =>2 ]);
        return redirect()->route('home')->with('success','メモを削除しました!');
    }


}
