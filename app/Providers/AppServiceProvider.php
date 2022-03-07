<?php



namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\Remind;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // 全てのメソッドが呼ばれる前に先に呼ばれるメソッド
            view()->composer('*', function ($view) {
                // get the current user
                $user = Auth::user();
                 // インスタンス化
                $memoModel = new Memo();
                $memos = $memoModel->myMemo( Auth::id() );
                
                // タグに取得
                $tagModel = new Tag();
                $tags = $tagModel->where('user_id', Auth::id())->get();
                

                $view->with('user', $user)->with('memos', $memos)->with('tags', $tags);
            });
    }
}