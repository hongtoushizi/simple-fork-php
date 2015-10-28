<?php

/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/10/23
 * Time: 16:58
 */
class PoolTest extends PHPUnit_Framework_TestCase
{
    public function testAliveCount()
    {
        $pool = new \Jenner\SimpleFork\Pool();
        for ($i = 0; $i < 10; $i++) {
            $process = new \Jenner\SimpleFork\Process(function () {
                sleep(3);
            });
            $pool->submit($process);
        }
        $pool->start();
        $this->assertEquals(10, $pool->aliveCount());
        $pool->wait();
    }

    public function testShutdown()
    {
        $pool = new \Jenner\SimpleFork\Pool();
        for ($i = 0; $i < 10; $i++) {
            $process = new \Jenner\SimpleFork\Process(function () {
                sleep(3);
            });
            $pool->submit($process);
        }
        $start = time();
        $pool->start();
        $pool->shutdown();
        $time = time() - $start;
        $this->assertTrue($time >= 3);
        $this->assertEquals(0, $pool->aliveCount());
    }

    public function testShutdownForce()
    {
        $pool = new \Jenner\SimpleFork\Pool();
        for ($i = 0; $i < 10; $i++) {
            $process = new \Jenner\SimpleFork\Process(function () {
                sleep(3);
            });
            $pool->submit($process);
        }
        $start = time();
        $pool->start();
        $pool->shutdownForce();
        $time = time() - $start;
        $this->assertTrue($time < 3);
        $this->assertEquals(0, $pool->aliveCount());
    }
}