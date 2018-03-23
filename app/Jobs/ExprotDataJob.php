<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 队列导出
 * 文档 http://www.maatwebsite.nl/laravel-excel/docs/export 可翻译查看
 * Class ExprotDataJob
 * @package App\Jobs
 */
class ExprotDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileName ;         // 文件名字
    private $tableHeader;       // 文件列头
    private $tableBody ;        // 文件数据

    /**
     * ExprotDataJob constructor.
     * $fileName 文件名
     * $tableHeader 文件头[姓名，性别]
     * $tableBody 文件数据 [[张三,男],[李四，女]]
     * @return void
     */
    public function __construct($fileName,$tableHeader,$tableBody){
        $this->fileName = $fileName;
        $this->tableHeader = $tableHeader;
        $this->tableBody = $tableBody;
    }

    /**
     * Execute the job.
     * storage_path() 缓存文件路径
     * @return void
     */
    public function handle(){
        $cellData = array_merge(array($this->tableHeader),$this->tableBody);
        Excel::create($this->fileName,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->store('xls',storage_path('app/excel/exports'));
    }

}
