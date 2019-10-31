<?php

/**
 * ストップウォッチクラス
 * @author jiro
 */
class StopWatch {

    /**
     * 計測中か停止中かを示す
     */
    private $status;
    const STATUS_RUN = 1;
    const STATUS_STOP = 2;

    /**
     * 最後に計測開始した時間
     */
    private $startAt;

    /**
     * 経過時間を示す
     */
    private $elapsed;

    public function __construct() {
        $this->status = self::STATUS_STOP;
        $this->elapsed = 0;
    }

    /**
     * 計測開始
     */
    public function start() {
        $this->startAt = microtime(true);
        $this->status = self::STATUS_RUN;
    }

    /**
     * 計測中止
     */
    public function stop() {
        $this->status = self::STATUS_STOP;
        $this->elapsed += microtime(true) - $this->startAt;
    }

    /**
     * 経過時間取得
     * @return number 経過時間
     */
    public function getElapsedMicroSec() {
        if($this->status === self::STATUS_STOP) {
            return $this->elapsed;
        } else {
            return $this->elapsed + (microtime(true) - $this->startAt);
        }
    }
}

/**
 * 時間計測用クラス
 * @author jiro
 */
class TimeKeeper {

    /**
     * このインスタンスの表題
     */
    public $title;

    /**
     * 表題が変えられた場合にtrueとなるフラグ
     */
    public $isTitleChanged = false;

    /**
     * startした回数
     */
    private $countStart = 0;

    /**
     * stopした回数
     */
    private $countStop = 0;

    /**
     * ストップウォッチ
     */
    private $stopWatch;

    /**
     * 表題とともにインスタンスを生成する
     * @param string $title インスタンスにつける表題
     */
    public function __construct($title) {
        $this->title = $title;
        $this->stopWatch = new StopWatch();
    }

    /**
     * 経過時間計測を開始する
     */
    public function start() {
        $this->stopWatch->start();
        $this->countStart++;
    }

    /**
     * 経過時間計測を終了する
     */
    public function stop() {
        $this->stopWatch->stop();
        $this->countStop++;
    }

    /**
     * 計測結果レポートを出力する
     * @return string 計測結果レポート
     */
    public function report() {
        $s = '';
        $s .= 'title=[' . $this->title . ']';
        $s .= ', start=' . $this->countStart;
        $s .= ', stop=' . $this->countStop;
        if ($this->countStart > 0) {
            $s .= ', totalSec=' . round($this->stopWatch->getElapsedMicroSec(), 2);
            $s .= ', aveSec=' . round(($this->stopWatch->getElapsedMicroSec() / $this->countStart), 2);
        } else {
            $s .= ', WARNING: not started.';
        }
        if ($this->isTitleChanged) {
            $s .= ', WARNING: title has changed.';
        }
        if ($this->countStart != $this->countStop) {
            $s .= ', WARNING: count start/stop not match.';
        }

        return $s;
    }
}

/**
 * 時間計測用クラスをまとめて扱うクラス
 * @author jiro
 */
class TimeKeeperManager {
    public $title;
    private $idxZeroAuto;
    private $timeKeepers = array();

    /**
     * 表題とともにインスタンスを生成する
     * @param string $title インスタンスにつける表題
     * @param boolean $idxZeroAuto 生成と同時に、添え字[0]の計測を開始する。
     */
    public function __construct($title, $idxZeroAuto = true) {
        $this->title = $title;
        if ($idxZeroAuto) {
            $this->start(0, 'TOTAL');
        }
        $this->idxZeroAuto = $idxZeroAuto;
    }

    /**
     * 表題指定つきで計測開始する
     * @param float $id 時計計測クラスの識別子
     * @param string $title 時計計測クラスの表題
     */
    public function start ($id, $title = '') {
        $idStr = strval($id);
        if (!array_key_exists($idStr, $this->timeKeepers)) {
            $this->timeKeepers[$idStr] = new TimeKeeper($title);
        } else {
            if ($this->timeKeepers[$idStr]->title != $title) {
                $this->timeKeepers[$idStr]->isTitleChanged = true;
            }
        }
        $this->timeKeepers[$idStr]->start();
    }

    /**
     * idを指定して計測停止する
     * @param float $id 時計計測クラスの識別子
     */
    public function stop($id) {
        $idStr = strval($id);
        $this->timeKeepers[$idStr]->stop();
    }

    /// <summary>
    /// 計測対象すべての結果を出力する
    /// </summary>
    /// <returns>計測結果を説明する文字列</returns>
    public function report() {
        if ($this->idxZeroAuto) {
            $this->stop(0);
        }
        $s = array();
        $s[] = '------------------------------------';
        $s[] = sprintf("Timekeepers %s report", $this->title);

        // timeKeepersをキー(数値)順に並べる
        $sortResult = ksort($this->timeKeepers, SORT_NUMERIC);
        if (!$sortResult) {
            $s[] = 'key sort failed';
        }

        foreach($this->timeKeepers as $key => $timeKeeper) {
            $s[] = $key . ': ' . $timeKeeper->report();
        }
        return implode(PHP_EOL, $s);
    }
}
