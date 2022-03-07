<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Memo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomeController;
use Log;

class SendReminderEmails extends Command
//メール送信するためのコマンド
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_remind_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインドメールを送ります';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('start');

        logger()->info(Memo::all()->last());
        
        //現在時刻とsend_atが一致したらデータを取得
        $reminds = Memo::where('send_at', Carbon::now()->format('Y-m-d H:i:00'))->get();
        logger()->info($reminds);
    {
        //メール内容
        foreach ($reminds as $remind)
                Mail::raw($remind->content. Carbon::now()->format('Y-m-d H:i:00'), function ($m) use($remind){
                $m->from('kyout222@gmail.com', 'リマインドメモ');
                $m->to($remind->email)->subject('メモの内容です。覚えていますか？');
            });
        }
        $this->info(Carbon::now()->format('Y-m-d H:m:00'));
        $this->info('Complete');

    }
}