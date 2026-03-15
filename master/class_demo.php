<?php
// ============================================
// PHP クラス（オブジェクト指向）デモ - 初学者向け
// ============================================

// --- 1. 基本的なクラス ---
echo "=== 1. 基本的なクラス ===" . PHP_EOL;

class Animal
{
    // プロパティ（クラスが持つデータ）
    public string $name;
    public string $sound;

    // コンストラクタ（インスタンス作成時に呼ばれる）
    public function __construct(string $name, string $sound)
    {
        $this->name = $name;
        $this->sound = $sound;
    }

    // メソッド（クラスが持つ機能）
    public function speak(): string
    {
        return $this->name . "は「" . $this->sound . "」と鳴きます";
    }
}

// インスタンスの作成と使用
$cat = new Animal("ネコ", "にゃー");
$dog = new Animal("イヌ", "わんわん");

echo $cat->speak() . PHP_EOL;
echo $dog->speak() . PHP_EOL;
echo PHP_EOL;

// --- 2. アクセス修飾子 ---
echo "=== 2. アクセス修飾子 ===" . PHP_EOL;

class BankAccount
{
    // public:  どこからでもアクセス可能
    public string $ownerName;

    // private: クラス内部からのみアクセス可能
    private int $balance;

    public function __construct(string $ownerName, int $balance)
    {
        $this->ownerName = $ownerName;
        $this->balance = $balance;
    }

    // 残高を安全に取得（ゲッター）
    public function getBalance(): int
    {
        return $this->balance;
    }

    // 入金
    public function deposit(int $amount): void
    {
        if ($amount > 0) {
            $this->balance += $amount;
            echo $amount . "円を入金しました" . PHP_EOL;
        }
    }

    // 出金
    public function withdraw(int $amount): void
    {
        if ($amount > 0 && $amount <= $this->balance) {
            $this->balance -= $amount;
            echo $amount . "円を出金しました" . PHP_EOL;
        } else {
            echo "残高不足です" . PHP_EOL;
        }
    }
}

$account = new BankAccount("太郎", 1000);
echo "口座名義: " . $account->ownerName . PHP_EOL;
echo "残高: " . $account->getBalance() . "円" . PHP_EOL;

$account->deposit(500);
echo "残高: " . $account->getBalance() . "円" . PHP_EOL;

$account->withdraw(2000);  // 残高不足
echo PHP_EOL;

// --- 3. 継承 ---
echo "=== 3. 継承 ===" . PHP_EOL;

// ポイント: 共通の処理を親クラスにまとめて、子クラスで違いだけを書く

// 親クラス: すべての従業員に共通する部分
class Employee
{
    public function __construct(
        protected string $name,     // protected: 子クラスからもアクセス可能
        protected int $baseSalary,
    ) {}

    // 給与計算（子クラスで上書きできる）
    public function calculateSalary(): int
    {
        return $this->baseSalary;
    }

    // 自己紹介
    public function introduce(): string
    {
        return $this->name . " - 月給: " . number_format($this->calculateSalary()) . "円";
    }
}

// 子クラス1: エンジニア（残業代がつく）
class Engineer extends Employee
{
    private int $overtimeHours;
    private int $overtimeRate;

    public function __construct(string $name, int $baseSalary, int $overtimeHours, int $overtimeRate = 2000)
    {
        parent::__construct($name, $baseSalary);  // 親のコンストラクタを呼ぶ
        $this->overtimeHours = $overtimeHours;
        $this->overtimeRate = $overtimeRate;
    }

    // 親メソッドをオーバーライド（上書き）して残業代を加算
    public function calculateSalary(): int
    {
        return $this->baseSalary + ($this->overtimeHours * $this->overtimeRate);
    }

    public function introduce(): string
    {
        return "[エンジニア] " . parent::introduce()
            . "（残業" . $this->overtimeHours . "h）";
    }
}

// 子クラス2: 営業（成果報酬がつく）
class SalesStaff extends Employee
{
    private int $salesAmount;
    private float $commissionRate;

    public function __construct(string $name, int $baseSalary, int $salesAmount, float $commissionRate = 0.05)
    {
        parent::__construct($name, $baseSalary);
        $this->salesAmount = $salesAmount;
        $this->commissionRate = $commissionRate;
    }

    // 売上に応じたインセンティブを加算
    public function calculateSalary(): int
    {
        return $this->baseSalary + (int)($this->salesAmount * $this->commissionRate);
    }

    public function introduce(): string
    {
        return "[営業] " . parent::introduce()
            . "（売上" . number_format($this->salesAmount) . "円）";
    }
}

// 子クラス3: マネージャー（役職手当がつく）
class Manager extends Employee
{
    private int $teamSize;
    private int $allowancePerMember;

    public function __construct(string $name, int $baseSalary, int $teamSize, int $allowancePerMember = 10000)
    {
        parent::__construct($name, $baseSalary);
        $this->teamSize = $teamSize;
        $this->allowancePerMember = $allowancePerMember;
    }

    public function calculateSalary(): int
    {
        return $this->baseSalary + ($this->teamSize * $this->allowancePerMember);
    }

    public function introduce(): string
    {
        return "[マネージャー] " . parent::introduce()
            . "（部下" . $this->teamSize . "人）";
    }
}

// --- 使ってみる ---

// 異なる職種の従業員を作成
$employees = [
    new Engineer("田中", 300000, 20),            // 残業20時間
    new SalesStaff("佐藤", 280000, 5000000),     // 売上500万
    new Manager("鈴木", 400000, 8),              // 部下8人
    new Employee("山本", 250000),                 // 一般社員（手当なし）
];

// 全員同じ introduce() で表示できる（ポリモーフィズム）
echo "--- 従業員一覧 ---" . PHP_EOL;
foreach ($employees as $employee) {
    echo "  " . $employee->introduce() . PHP_EOL;
}

// 合計給与の計算も簡単
$totalSalary = 0;
foreach ($employees as $employee) {
    $totalSalary += $employee->calculateSalary();
}
echo "--- 給与合計: " . number_format($totalSalary) . "円 ---" . PHP_EOL;
echo PHP_EOL;

// --- 4. インターフェース ---
echo "=== 4. インターフェース ===" . PHP_EOL;

// インターフェース = 「このメソッドを必ず実装してね」という約束
interface Printable
{
    public function toString(): string;
}

class Student implements Printable
{
    public function __construct(
        private string $name,
        private int $grade,
    ) {}

    public function toString(): string
    {
        return $this->name . "（" . $this->grade . "年生）";
    }
}

class Teacher implements Printable
{
    public function __construct(
        private string $name,
        private string $subject,
    ) {}

    public function toString(): string
    {
        return $this->name . "先生（" . $this->subject . "担当）";
    }
}

// 同じインターフェースなので同じように扱える
$people = [
    new Student("花子", 2),
    new Teacher("山田", "数学"),
    new Student("次郎", 3),
];

foreach ($people as $person) {
    echo "  " . $person->toString() . PHP_EOL;
}
echo PHP_EOL;

// --- 5. staticメソッド ---
echo "=== 5. staticメソッド ===" . PHP_EOL;

class MathHelper
{
    // staticメソッド = インスタンスを作らずに呼べる
    public static function add(int $a, int $b): int
    {
        return $a + $b;
    }

    public static function isEven(int $n): bool
    {
        return $n % 2 === 0;
    }
}

// クラス名::メソッド名() で呼び出し
echo "3 + 7 = " . MathHelper::add(3, 7) . PHP_EOL;
echo "4は偶数？ " . (MathHelper::isEven(4) ? "はい" : "いいえ") . PHP_EOL;
echo "5は偶数？ " . (MathHelper::isEven(5) ? "はい" : "いいえ") . PHP_EOL;
