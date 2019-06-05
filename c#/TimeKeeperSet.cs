using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Text;

namespace TimeKeeperSet {

    /// <summary>
    /// 時間計測用クラスをまとめて扱うクラス
    /// </summary>
    public class TimeKeeperManager {
        string title;
        private SortedDictionary<double, TimeKeeper> timeKeepers = new SortedDictionary<double, TimeKeeper>();

        /// <summary>
        /// 表題なしでインスタンスを生成する
        /// </summary>
        public TimeKeeperManager() : this(""){} 
        /// <summary>
        /// 表題とともにインスタンスを生成する
        /// </summary>
        /// <param name="title">インスタンスにつける表題</param>
        public TimeKeeperManager(String title) {
            this.title = title;
        }

        /// <summary>
        /// 計測開始する
        /// </summary>
        /// <param name="id">時計計測クラスの識別子</param>
        public void start(double id) {
            this.start (id, "");
        }

        /// <summary>
        /// 表題指定つきで計測開始する
        /// </summary>
        /// <param name="id">時計計測クラスの識別子</param>
        /// <param name="title">時計計測クラスの表題</param>
        public void start (double id, string title) {
            if (!timeKeepers.ContainsKey(id)) {
                timeKeepers.Add(id, new TimeKeeper(title));
            } else {
                if (!timeKeepers[id].title.Equals(title)) {
                    timeKeepers[id].isTitleChanged = true;
                }
            }
            timeKeepers[id].start();
        }

        /// <summary>
        /// idを指定して計測停止する
        /// </summary>
        /// <param name="id">時計計測クラスの識別子</param>
        public void stop(double id) {
            timeKeepers[id].stop();
        }

        /// <summary>
        /// 計測対象すべての結果を出力する
        /// </summary>
        /// <returns>計測結果を説明する文字列</returns>
        public string report() {
            StringBuilder sb = new StringBuilder();
            sb.Append("------------------------------------\n");
            sb.Append(string.Format("Timekeepers {0} report\n", title));
            foreach(KeyValuePair<double, TimeKeeper> kvp in timeKeepers) {
                sb.Append(kvp.Key + ": " + kvp.Value.report() + "\n");
            }
            return sb.ToString();
        }

        /// <summary>
        /// 時間計測用クラス
        /// </summary>
        class TimeKeeper {
            /// <summary>
            /// このインスタンスの表題
            /// </summary>
            public String title {get;set;}
            /// <summary>
            /// 表題が変えられた場合にtrueとなるフラグ
            /// </summary>
            public bool isTitleChanged {get;set;}

            /// <summary>
            /// startした回数
            /// </summary>
            private int countStart = 0;
            /// <summary>
            /// stopした回数
            /// </summary>
            private int countStop = 0;
            /// <summary>
            /// startしてからstopするまでの経過時間を保有する
            /// </summary>
            private Stopwatch sw = new Stopwatch();
            /// <summary>
            /// 表題とともにインスタンスを生成する
            /// </summary>
            /// <param name="title">インスタンスにつける表題</param>
            public TimeKeeper (String title) {
                this.title = title;
                this.isTitleChanged = false;
            }
            /// <summary>
            /// 表題なしでインスタンスを生成する
            /// </summary>
            public TimeKeeper () : this(""){}

            /// <summary>
            /// 経過時間計測を開始する
            /// </summary>
            public void start() {
                sw.Start();
                countStart++;
            }

            /// <summary>
            /// 経過時間計測を終了する
            /// </summary>
            public void stop() {
                sw.Stop();
                countStop++;
            }

            /// <summary>
            /// 計測結果レポートを出力する
            /// </summary>
            /// <returns>計測結果を説明する文字列</returns>
            public string report() {

                float elapsed = sw.ElapsedMilliseconds;
                System.Text.StringBuilder sb = new System.Text.StringBuilder();
                sb.Append(string.Format("start={0}", countStart));
                sb.Append(string.Format(", stop={0}", countStop));
                sb.Append(string.Format(", totalSec={0:F2}", elapsed/1000));
                sb.Append(string.Format(", aveSec={0:F2}", (elapsed/1000/countStart)));
                if (title != "") {
                    sb.Append(string.Format(", title={0}", title));
                }
                if (isTitleChanged) {
                    sb.Append(", WARNING: title has changed.");
                }
                if (countStart != countStop) {
                    sb.Append(", WARNING: count start/stop not match.");
                }
                return sb.ToString();
            }
        }
    }
}
