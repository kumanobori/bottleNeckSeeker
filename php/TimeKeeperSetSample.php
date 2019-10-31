<?php
require_once('TimeKeeperSet.php');


$obj = new Sample();
$obj->exec();
exit;

class Sample {
    public function exec() {
        $this->testStopWatch();
        $this->testTimeKeeper();
        $this->testTimeKeeperManager();
    }

    private function log($str) {
        echo sprintf("%s [LOG] %s%s", date('Y-m-d H:i:s'), $str, PHP_EOL);
    }


    private function testStopWatch() {
       $s = new StopWatch();
       $s->start();
       usleep(1600000); // 1.6秒
       $s->stop();
       $this->log($s->getElapsedMicroSec());
       $s->start();
       usleep(1600000); // 1.6秒
       $this->log($s->getElapsedMicroSec());
       usleep(1600000); // 1.6秒
       $this->log($s->getElapsedMicroSec());
    }

    private function testTimeKeeper() {
        $tk = new TimeKeeper('aaaaa');
        $tk->start();
        usleep(1600000); // 1.6秒
        $tk->stop();
        $this->log($tk->report());
    }

    private function testTimeKeeperManager() {
        $tkm = new TimeKeeperManager("aaa");
        $tkm->start(1, 'abcdefg'); // タイトルがreportに表示される
        $this->phase01();
        $tkm->stop(1);
        $tkm->start(2); // タイトルがreportに表示されない
        for ($i = 0; $i < 3; $i++) {
            $tkm->start(2.1); // id=2の子項目的に追加した体
            $this->phase03();
            $tkm->stop(2.1); // id=2の子項目的に追加した体
            for ($j = 0; $j < 2; $j++) {
                $tkm->start(2.21); // id=2の子項目的に追加した体
                $this->phase04();
                $tkm->stop(2.21); // id=2の子項目的に追加した体
            }
            $tkm->start(2.2);
            $this->phase05();
            $tkm->stop(2.2);
        }
        $tkm->stop(2);
        $tkm->start(6, "phase6");
        $this->phase06();
        $tkm->stop(6);
        $tkm->start(7, "comment ver 1");
        $this->phase07();
        $tkm->stop(7);
        $tkm->start(7, "comment ver 2"); // 同じIDを使ってしまったパターン（レポートに警告が出る）
        $this->phase08();
        $tkm->stop(7);
        $tkm->start(10);
        $this->phase09();
        $tkm->stop(10);
        $tkm->start(9); // IDの呼び出し順が昇順でないパターン（レポートはID順に出る）・かつ、stopしたままで終了するパターン（レポートに警告が出る）
        $this->phase10();
        echo $tkm->report();

        // startZero = false
        $tkm2 = new TimeKeeperManager('bbbbb', false);
        $tkm2->start(2, 'ccccc');
        $this->wait();
        $tkm2->stop(2);
        $tkm2->report();
        echo $tkm2->report();
    }

    private function wait() {
        sleep(1);
    }
    private function phase01() { $this->wait(); }
    private function phase02() { $this->wait(); }
    private function phase03() { $this->wait(); }
    private function phase04() { $this->wait(); }
    private function phase05() { $this->wait(); }
    private function phase06() { $this->wait(); }
    private function phase07() { $this->wait(); }
    private function phase08() { $this->wait(); }
    private function phase09() { $this->wait(); }
    private function phase10() { $this->wait(); }

}