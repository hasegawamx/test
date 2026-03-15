<?php
// ============================================
// PHP 基礎デモ - 初学者向けサンプルコード
// ============================================

// --- 1. 文字列の出力 ---
echo "=== 1. 文字列の出力 ===" . PHP_EOL;
echo "Hello, PHP!" . PHP_EOL;
echo PHP_EOL;

// --- 2. 変数 ---
echo "=== 2. 変数 ===" . PHP_EOL;
$name = "太郎";          // 文字列
$age = 20;               // 整数
$height = 170.5;         // 小数（浮動小数点数）
$isStudent = true;       // 真偽値（boolean）

echo "名前: " . $name . PHP_EOL;
echo "年齢: " . $age . "歳" . PHP_EOL;
echo "身長: " . $height . "cm" . PHP_EOL;
echo PHP_EOL;

// --- 3. 配列 ---
echo "=== 3. 配列 ===" . PHP_EOL;

// インデックス配列
$fruits = ["りんご", "みかん", "ぶどう"];
echo "好きな果物: " . $fruits[0] . PHP_EOL;  // りんご（0番目）

// 連想配列（キーと値のペア）
$user = [
    "name" => "太郎",
    "age"  => 20,
    "city" => "東京",
];
echo "住所: " . $user["city"] . PHP_EOL;
echo PHP_EOL;

// --- 4. 条件分岐（if文） ---
echo "=== 4. 条件分岐 ===" . PHP_EOL;

if ($age >= 20) {
    echo $name . "さんは成人です" . PHP_EOL;
} else {
    echo $name . "さんは未成年です" . PHP_EOL;
}
echo PHP_EOL;

// --- 5. ループ（繰り返し） ---
echo "=== 5. ループ ===" . PHP_EOL;

// for文
echo "カウント: ";
for ($i = 1; $i <= 5; $i++) {
    echo $i . " ";
}
echo PHP_EOL;

// foreach文（配列を順に処理）
echo "果物一覧:" . PHP_EOL;
foreach ($fruits as $index => $fruit) {
    echo "  " . ($index + 1) . ". " . $fruit . PHP_EOL;
}
echo PHP_EOL;

// --- 6. 関数 ---
echo "=== 6. 関数 ===" . PHP_EOL;

// 関数の定義
function greet(string $name): string
{
    return "こんにちは、" . $name . "さん！";
}

// 関数の呼び出し
echo greet("花子") . PHP_EOL;
echo greet($name) . PHP_EOL;
echo PHP_EOL;

// --- 7. 型の確認 ---
echo "=== 7. 型の確認 ===" . PHP_EOL;
echo '$name の型: '   . gettype($name)      . PHP_EOL;  // string
echo '$age の型: '    . gettype($age)       . PHP_EOL;  // integer
echo '$height の型: ' . gettype($height)    . PHP_EOL;  // double
echo '$isStudent の型: ' . gettype($isStudent) . PHP_EOL; // boolean
echo '$fruits の型: ' . gettype($fruits)    . PHP_EOL;  // array
