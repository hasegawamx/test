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

// 親クラス
class Shape
{
    public string $color;

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function describe(): string
    {
        return $this->color . "の図形";
    }
}

// 子クラス（Shapeを継承）
class Circle extends Shape
{
    public float $radius;

    public function __construct(string $color, float $radius)
    {
        parent::__construct($color);  // 親のコンストラクタを呼ぶ
        $this->radius = $radius;
    }

    public function area(): float
    {
        return M_PI * $this->radius ** 2;
    }

    // 親メソッドのオーバーライド（上書き）
    public function describe(): string
    {
        return $this->color . "の円（半径" . $this->radius . "）";
    }
}

class Rectangle extends Shape
{
    public float $width;
    public float $height;

    public function __construct(string $color, float $width, float $height)
    {
        parent::__construct($color);
        $this->width = $width;
        $this->height = $height;
    }

    public function area(): float
    {
        return $this->width * $this->height;
    }

    public function describe(): string
    {
        return $this->color . "の長方形（" . $this->width . "x" . $this->height . "）";
    }
}

$circle = new Circle("赤", 5.0);
$rect = new Rectangle("青", 4.0, 3.0);

echo $circle->describe() . " → 面積: " . round($circle->area(), 2) . PHP_EOL;
echo $rect->describe() . " → 面積: " . $rect->area() . PHP_EOL;
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
