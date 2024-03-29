using System;
using TimeKeeperSet;
using System.Threading.Tasks;

namespace HelloWorld
{
    class Hello 
    {
        static void Main() 
        {
            TimeKeeperManager tkm = new TimeKeeperManager("aaa");
            tkm.start(0, "総合計"); // タイトルがreportに表示される
            tkm.start(1); // タイトルがreportに表示されない
            phase01();
            tkm.stop(1);
            tkm.start(2);
            for (int i = 0; i < 3; i++) {
                tkm.start(2.1); // id=2の子項目的に追加した体
                phase03();
                tkm.stop(2.1); // id=2の子項目的に追加した体
                for (int j = 0; j < 2; j++) {
                    tkm.start(2.21); // id=2の子項目的に追加した体
                    phase04();
                    tkm.stop(2.21); // id=2の子項目的に追加した体
                }
                tkm.start(2.2);
                phase05();
                tkm.stop(2.2);
            }
            tkm.stop(2);
            tkm.start(6, "phase6");
            phase06();
            tkm.stop(6);
            tkm.start(7, "comment ver 1");
            phase07();
            tkm.stop(7);
            tkm.start(7, "comment ver 2"); // 同じIDを使ってしまったパターン（レポートに警告が出る）
            phase08();
            tkm.stop(7);
            tkm.start(10);
            phase09();
            tkm.stop(10);
            tkm.start(9); // IDの呼び出し順が昇順でないパターン（レポートはID順に出る）・かつ、stopしたままで終了するパターン（レポートに警告が出る）
            phase10();
            
            tkm.stop(0);
            Console.WriteLine(tkm.report());
        }

        static void wait() { System.Threading.Thread.Sleep(300); }
        static void phase01() => wait();
        static void phase02() => wait();
        static void phase03() => wait();
        static void phase04() => wait();
        static void phase05() => wait();
        static void phase06() => wait();
        static void phase07() => wait();
        static void phase08() => wait();
        static void phase09() => wait();
        static void phase10() => wait();
    }
}