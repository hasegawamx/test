<?php
// ============================================
// PHP エラーハンドリングデモ - 初学者向け
// ============================================

// --- 1. 基本的な try / catch ---
echo "=== 1. 基本的な try / catch ===" . PHP_EOL;

// try の中でエラー（例外）が発生すると、catch に飛ぶ
try {
    // 手動で例外を投げてみる
    throw new Exception("これはテスト用の例外です");

    // ↑ で例外が発生するので、この行は実行されない
    echo "この行は表示されません" . PHP_EOL;
} catch (Exception $e) {
    // $e にエラー情報が入っている
    echo "エラー発生: " . $e->getMessage() . PHP_EOL;
}

// ゼロ除算エラーも catch できる
try {
    $result = 10 / 0;
} catch (DivisionByZeroError $e) {
    echo "ゼロ除算エラー: " . $e->getMessage() . PHP_EOL;
}
echo "→ catchの後も処理は続きます" . PHP_EOL;
echo PHP_EOL;

// --- 2. 複数の例外を使い分ける ---
echo "=== 2. 複数の例外を使い分ける ===" . PHP_EOL;

function divide(int $a, int $b): float
{
    if ($b === 0) {
        throw new InvalidArgumentException("0で割ることはできません");
    }
    return $a / $b;
}

function parseAge(string $input): int
{
    if (!is_numeric($input)) {
        throw new InvalidArgumentException("数値を入力してください: " . $input);
    }
    $age = (int)$input;
    if ($age < 0 || $age > 150) {
        throw new RangeException("年齢は0〜150の範囲で入力してください: " . $age);
    }
    return $age;
}

// 異なる種類の例外をそれぞれ catch できる
$testCases = [
    ["action" => "divide(10, 3)"],
    ["action" => "divide(10, 0)"],
    ["action" => "parseAge('25')"],
    ["action" => "parseAge('abc')"],
    ["action" => "parseAge('200')"],
];

foreach ($testCases as $test) {
    try {
        $result = eval("return " . $test["action"] . ";");
        echo "  " . $test["action"] . " → " . $result . PHP_EOL;
    } catch (InvalidArgumentException $e) {
        echo "  " . $test["action"] . " → 引数エラー: " . $e->getMessage() . PHP_EOL;
    } catch (RangeException $e) {
        echo "  " . $test["action"] . " → 範囲エラー: " . $e->getMessage() . PHP_EOL;
    }
}
echo PHP_EOL;

// --- 3. カスタム例外クラス ---
echo "=== 3. カスタム例外クラス ===" . PHP_EOL;

// 独自の例外を作ると、エラーの種類がわかりやすくなる
class InsufficientFundsException extends RuntimeException
{
    public function __construct(int $requested, int $available)
    {
        $message = number_format($requested) . "円の出金に対して残高が"
            . number_format($available) . "円しかありません";
        parent::__construct($message);
    }
}

class AccountLockedException extends RuntimeException
{
    public function __construct(string $reason)
    {
        parent::__construct("口座がロックされています: " . $reason);
    }
}

class SimpleBankAccount
{
    private bool $locked = false;

    public function __construct(
        private string $owner,
        private int $balance,
    ) {}

    public function lock(string $reason): void
    {
        $this->locked = true;
        echo "  → " . $this->owner . "の口座をロックしました（" . $reason . "）" . PHP_EOL;
    }

    public function withdraw(int $amount): void
    {
        if ($this->locked) {
            throw new AccountLockedException("不正利用の疑い");
        }
        if ($amount > $this->balance) {
            throw new InsufficientFundsException($amount, $this->balance);
        }
        $this->balance -= $amount;
        echo "  → " . number_format($amount) . "円を出金（残高: "
            . number_format($this->balance) . "円）" . PHP_EOL;
    }
}

$account = new SimpleBankAccount("太郎", 10000);

$operations = [3000, 5000, 8000];  // 3回出金を試みる
foreach ($operations as $amount) {
    try {
        echo "  " . number_format($amount) . "円を出金..." . PHP_EOL;
        $account->withdraw($amount);
    } catch (InsufficientFundsException $e) {
        echo "  残高不足: " . $e->getMessage() . PHP_EOL;
    }
}

// ロックされた口座
$account->lock("不正利用の疑い");
try {
    $account->withdraw(100);
} catch (AccountLockedException $e) {
    echo "  ロック: " . $e->getMessage() . PHP_EOL;
}
echo PHP_EOL;

// --- 4. finally ---
echo "=== 4. finally ===" . PHP_EOL;

// finally は成功でもエラーでも必ず実行される（後片付け用）
function processFile(string $filename): void
{
    echo "  ファイル処理を開始: " . $filename . PHP_EOL;
    try {
        if (!file_exists($filename)) {
            throw new RuntimeException("ファイルが見つかりません");
        }
        echo "  ファイルを処理中..." . PHP_EOL;
    } catch (RuntimeException $e) {
        echo "  エラー: " . $e->getMessage() . PHP_EOL;
    } finally {
        // 成功してもエラーでもここは必ず通る
        echo "  → 後片付け完了（finallyブロック）" . PHP_EOL;
    }
}

processFile("存在しないファイル.txt");
echo PHP_EOL;

// --- 5. まとめ ---
echo "=== まとめ ===" . PHP_EOL;
echo "  try     → エラーが起きるかもしれない処理を書く" . PHP_EOL;
echo "  catch   → エラーが起きた時の対処を書く" . PHP_EOL;
echo "  finally → 成功でもエラーでも必ず実行される" . PHP_EOL;
echo "  throw   → 自分でエラーを発生させる" . PHP_EOL;
echo "  カスタム例外 → エラーの種類を明確にできる" . PHP_EOL;
