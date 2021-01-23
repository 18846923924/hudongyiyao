<?php
namespace contrab;

class DispatcherTask
{
    /**
     * @var ContrabInterface
     */
    protected $contrab;

    /**
     * @var
     */
    protected $cacheDir;

    /**
     * @var
     */
    protected $times;

    /**
     * @var string  缓存文件
     */
    protected $cacheFile;

    /**
     * DispatcherTask constructor.
     * @param ContrabInterface $contrab
     * @param $cacheDir
     */
    public function __construct(ContrabInterface $contrab, $cacheDir)
    {
        $this->contrab = $contrab;
        $this->cacheDir = $cacheDir;

        if (!$contrab->contrabName) {
            throw new \Exception('the contrabName is null!');
        }

        $this->cacheFile = $cacheDir . md5($contrab->contrabName);
    }

    /**
     * @return ContrabInterface
     */
    public function getContrab()
    {
        return $this->contrab;
    }

    /**
     * @throws \Exception
     */
    public function start()
    {
       $timeClass   = $this->contrab->setContrab();

       if ($this->contrab->isStop === true) {
           return null;
       }

       $times       = $timeClass->getTime();
       $this->createCacheFile($timeClass->getStartTime());

       switch((int)$timeClass->getRate()) {
           case 1: $this->times = 1 * $times; break;
           case 2: $this->times = 60 * $times; break;
           case 3: $this->times = 3600 * $times;break;
           case 4: $this->times = 86400 * $times; break;
           case 0: $this->_praseDate($timeClass->getStartTime()); break;
       }

       $this->runWithSecond();
    }

    /**
     * @return bool|int
     */
    public function getFileLastTime()
    {
        if (!file_exists($this->cacheFile)) {
            $this->createCacheFile('');
            return time();
        }
        return (int) file_get_contents($this->cacheFile);
    }

    /**
     * @param $startTime string
     * @return bool
     */
    public function createCacheFile($startTime)
    {
        $touchTime = time();
        if (!file_exists($this->cacheFile)) {
            if (date('Y-m-d H:i:s', strtotime($startTime)) == $startTime) {
                $touchTime = strtotime($startTime);
            }
            return file_put_contents($this->cacheFile, $touchTime);
        }
        return true;
    }

    /**
     * @return bool|int|void
     */
    public function updateCacheFile()
    {
        return file_put_contents($this->cacheFile, time());
    }

    /**
     * @return mixed
     */
    private function running()
    {
        $this->contrab->_init();
        $this->updateCacheFile();
    }

    /**
     * @return bool
     */
    protected function runWithSecond()
    {
        if (!$this->times) {
            return null;
        }

        $lastTime = $this->getFileLastTime();
        if (time() == $lastTime || (time() - $lastTime) % $this->times == 0) {
            $this->running();
        }
        return true;
    }

    /**
     * @param $times
     */
    protected function _praseDate($times)
    {
        $this->times = false;
        if (strtotime($times) == time()) {
            $this->running();
        }
    }
}