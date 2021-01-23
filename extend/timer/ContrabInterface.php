<?php
namespace contrab;

interface ContrabInterface
{
    /**
     * 设置一个定时任务执行时间
     * @return mixed
     */
    public function setContrab();

    /**
     * @return mixed
     */
    public function _init();
}