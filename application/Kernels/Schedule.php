<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 * Date: 2019/4/19
 * Time: 14:31
 */

namespace app\Kernels;


use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;

class Schedule extends Command
{
    protected $signature = 'schedule:worker';

    protected $description = 'deal with the timed tasks';

    protected function configure()
    {
        parent::configure();
        $this->setName('schedule')
             ->setDescription('Command task');
    }

    protected function execute(Input $input, Output $output)
    {
        ignore_user_abort(true);
        set_time_limit(1800);//30分钟
        $now = time();
        $time = date('H-i',$now);//当前时间时分
        $w_time = date('d',$now);//当前时间时分
        $hour = date('i',$now);
        //处理每日任务
        $tasks = $this->getDailyTasks($time,$w_time);
        if($tasks)
        {
            foreach ($tasks as $task)
            {
                try {
                    call_user_func_array([$task['class'], $task['func']], $task['params']);
                }catch (\Exception $e)
                {
                    Log::info('定时任务执行失败！TaskClass:'.$task['class'].' 错误信息:'.$e->getMessage().';位置：'.$e->getLine().$e->getTraceAsString());
                }
            }
        }
        $time = date('i');// 每小时执行一次
        $tasks = $this->getHourTasks($time);
        if($tasks)
        {
            foreach ($tasks as $task)
            {
                try {
                    call_user_func_array([$task['class'], $task['func']], $task['params']);
                }catch (\Exception $e)
                {
                    Log::info('定时任务执行失败！TaskClass:'.$task['class'].' 错误信息:'.$e->getMessage().';位置：'.$e->getLine().$e->getTraceAsString());
                }
            }
        }
        //处理每分钟执行的任务
        $tasks = $this->getMinuteTasks();
        if($tasks)
        {
            foreach ($tasks as $task)
            {
                try {
                    call_user_func_array([$task['class'], $task['func']], $task['params']);
                }catch (\Exception $e)
                {
                    Log::info('定时任务执行失败！TaskClass:'.$task['class'].' 错误信息:'.$e->getMessage().';位置：'.$e->getLine().$e->getTraceAsString());
                }
            }
        }


    }

    /**每日执行一次的任务
     * @param $time H-i
     * @param $w_time w-H-i
     * @return array
     */
    private function getDailyTasks($time,$w_time)
    {
        $tasks = [
            // 每日释放LIT（废除每日释放）
            '00-01'=>[
                // 清空签到情况
                [
                    'class'=>'app\tasks\SignClear',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],
            '00-10'=>[
                // 自动确认收货
                [
                    'class'=>'app\tasks\OrderConfirm',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],

            '00-20'=>[
                // 初始化店铺账户
                [
                    'class'=>'app\tasks\InitShopAccount',
                    'func'=>'run',
                    'params'=>['time'=>$w_time],
                ],
            ],
            '00-30'=>[
                // 每天统计近30天销量
                [
                    'class'=>'app\tasks\GoodsSales',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],
            //每天凌晨00：01执行
            '00-40'=>[
                // 清理过期优惠券
                [
                    'class'=>'app\tasks\CouponExpire',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],
            '00-50'=>[
                // 订单分佣结算
                [
                    'class'=>'app\tasks\OrderShareSettlement',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],

        ];
        if(key_exists($time,$tasks))
            return $tasks[$time];
        return null;
    }

    private function getMinuteTasks()
    {
        $tasks = [
            // 自动取消订单
            [
                'class'=>'app\tasks\OrderCancel',
                'func'=>'run',
                'params'=>[],
            ],
        ];
        return $tasks;
    }

    private function getHourTasks($time)
    {
        $tasks = [
            '00'=>[
                // 活动开始
                [
                    'class'=>'app\tasks\ActivityPurchaseStatus',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],
            '05'=>[
                // 活动结束，改变商品状态
                [
                    'class'=>'app\tasks\ActivityGoodsStatus',
                    'func'=>'run',
                    'params'=>[],
                ],
            ],
        ];
        if(key_exists($time,$tasks))
            return $tasks[$time];
        return $tasks['05'];
        return null;
    }

}