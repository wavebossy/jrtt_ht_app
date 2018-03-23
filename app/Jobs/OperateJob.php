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
 * 队列使用步骤
 * 1、确保服务器守护线程已开启 ： php artisan queue:work
 * 2、确保服务器Redis 装载队列任务已开启 redis-server &
 * 3、确保1,2点正常，即可使用队列任务
 */

/**
 * 队列导出 - 小编收益【可按月份导出工资表excel】
 * Class OperateJob
 * @package App\Jobs
 */
class OperateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    private $operate ;

    public function __construct(){

    }

//    队列处理的事情
    public function handle(){

    }

}
