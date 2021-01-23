<?php
/**
 * 创建时间： 2018-06-04
 * 定时任务处理
 */
namespace contrab;

use think\console\Input;
use think\console\Output;

class Command extends \think\console\Command
{
    protected function configure()
    {
        $this->setName('contrab')->setDescription('this is a mini contrab manager tool!');
    }

    protected function execute(Input $input, Output $output)
    {
        $contrab = new Dispatcher(Env::get('runtime_path') . '/contrab_cache/', Env::get('app_path') . '/common/contrab/');
        $contrab->addTask();
        $contrab->boot();
    }
}