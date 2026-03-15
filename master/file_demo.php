<?php
// ============================================
// PHP ファイル操作デモ - 初学者向け
// ============================================

// 作業用ディレクトリ（このスクリプトと同じ場所に作る）
$workDir = __DIR__ . "/demo_files";

// --- 準備: 作業フォルダを作成 ---
if (!is_dir($workDir)) {
    mkdir($workDir);
    echo "作業フォルダを作成しました: demo_files/" . PHP_EOL;
}
echo PHP_EOL;

// --- 1. テキストファイルの書き込み ---
echo "=== 1. テキストファイルの書き込み ===" . PHP_EOL;

$filePath = $workDir . "/sample.txt";

// file_put_contents: 一番シンプルな書き込み方法
file_put_contents($filePath, "こんにちは、PHP！\n");
echo "ファイルを作成しました: sample.txt" . PHP_EOL;

// 追記する場合は FILE_APPEND フラグをつける
file_put_contents($filePath, "2行目を追加しました\n", FILE_APPEND);
file_put_contents($filePath, "3行目も追加しました\n", FILE_APPEND);
echo "内容を追記しました" . PHP_EOL;
echo PHP_EOL;

// --- 2. テキストファイルの読み込み ---
echo "=== 2. テキストファイルの読み込み ===" . PHP_EOL;

// 方法A: 全体を一度に読む
echo "[方法A] file_get_contents:" . PHP_EOL;
$content = file_get_contents($filePath);
echo $content;

// 方法B: 1行ずつ配列で読む
echo "[方法B] file（配列で取得）:" . PHP_EOL;
$lines = file($filePath, FILE_IGNORE_NEW_LINES);  // 改行を除去
foreach ($lines as $index => $line) {
    echo "  " . ($index + 1) . "行目: " . $line . PHP_EOL;
}
echo PHP_EOL;

// --- 3. ファイルの存在確認とサイズ ---
echo "=== 3. ファイル情報 ===" . PHP_EOL;

echo "ファイルが存在する？ " . (file_exists($filePath) ? "はい" : "いいえ") . PHP_EOL;
echo "ファイルサイズ: " . filesize($filePath) . " バイト" . PHP_EOL;
echo "最終更新日時: " . date("Y-m-d H:i:s", filemtime($filePath)) . PHP_EOL;
echo PHP_EOL;

// --- 4. CSV ファイルの書き込みと読み込み ---
echo "=== 4. CSV ファイル ===" . PHP_EOL;

$csvPath = $workDir . "/members.csv";

// CSVに書き込み
$members = [
    ["名前", "年齢", "職業"],        // ヘッダー行
    ["田中太郎", 28, "エンジニア"],
    ["佐藤花子", 32, "デザイナー"],
    ["鈴木次郎", 25, "営業"],
];

$fp = fopen($csvPath, "w");
foreach ($members as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
echo "CSVファイルを作成しました: members.csv" . PHP_EOL;

// CSVを読み込み
echo "CSVの中身:" . PHP_EOL;
$fp = fopen($csvPath, "r");
$isHeader = true;
while (($row = fgetcsv($fp)) !== false) {
    if ($isHeader) {
        echo "  [ヘッダー] " . implode(" | ", $row) . PHP_EOL;
        $isHeader = false;
    } else {
        echo "  " . $row[0] . "さん（" . $row[1] . "歳、" . $row[2] . "）" . PHP_EOL;
    }
}
fclose($fp);
echo PHP_EOL;

// --- 5. JSON ファイル ---
echo "=== 5. JSON ファイル ===" . PHP_EOL;

$jsonPath = $workDir . "/config.json";

// 連想配列 → JSON に変換して保存
$config = [
    "appName"  => "My PHP App",
    "version"  => "1.0.0",
    "debug"    => true,
    "database" => [
        "host" => "localhost",
        "port" => 3306,
    ],
];

// JSON_PRETTY_PRINT: 人間が読みやすい形式にする
// JSON_UNESCAPED_UNICODE: 日本語をそのまま出力する
file_put_contents($jsonPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "JSONファイルを作成しました: config.json" . PHP_EOL;

// JSONを読み込み → 連想配列に戻す
$loaded = json_decode(file_get_contents($jsonPath), true);
echo "アプリ名: " . $loaded["appName"] . PHP_EOL;
echo "バージョン: " . $loaded["version"] . PHP_EOL;
echo "DBホスト: " . $loaded["database"]["host"] . PHP_EOL;
echo PHP_EOL;

// --- 6. ディレクトリ内のファイル一覧 ---
echo "=== 6. ファイル一覧 ===" . PHP_EOL;

echo "demo_files/ の中身:" . PHP_EOL;
$files = scandir($workDir);
foreach ($files as $file) {
    if ($file === "." || $file === "..") {
        continue;  // 現在と親ディレクトリはスキップ
    }
    $fullPath = $workDir . "/" . $file;
    $size = filesize($fullPath);
    echo "  " . $file . "（" . $size . " バイト）" . PHP_EOL;
}
echo PHP_EOL;

// --- 7. 後片付け ---
echo "=== 7. 後片付け ===" . PHP_EOL;

// 作成したファイルとフォルダを削除
$filesToDelete = glob($workDir . "/*");
foreach ($filesToDelete as $file) {
    unlink($file);  // ファイル削除
    echo "  削除: " . basename($file) . PHP_EOL;
}
rmdir($workDir);  // 空のフォルダを削除
echo "作業フォルダを削除しました" . PHP_EOL;
echo PHP_EOL;

echo "※ このデモは実行のたびにファイルを作成→削除するので安全です" . PHP_EOL;
