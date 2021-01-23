<?php
/**
 * 创建时间：   2018-05-31
 * 定时任务 任务设置抽象类
 */

namespace contrab;

abstract class ContrabAbstract implements ContrabInterface
{
    /**
     * @var null
     */
    public $contrabName = NULL;

    /**
     * @var bool
     */
    public $isStop = false;
}