<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 最大失败次数
    //public $tries = 5;

    // 超时时间
    // public $timeout = 120;

    private $key;
    private $value;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        Redis::hset('queue.test', $this->key, $this->value);
        Log::info($this->key.Date("YmdHis"));

        $this->exportData("sss_".time(),null,null);

    }

    private function exportData($fileName,$tableHeader,$tableBody){
        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
//        $cellData = [$tableHeader,$tableBody];
//        $cellData = array_merge(array($tableHeader),$tableBody);
//        dd($cellData);
        return Excel::create($fileName,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->store('xls',storage_path('excel/exports')); // ->download('xls');
    }

}
