<?php
// ============================================
// PHP データベースデモ（SQLite + PDO）- 初学者向け
// ============================================
// SQLite はインストール不要でファイル1つで動くDBです
// PDO は PHP でDBを操作する標準的な方法です

$dbPath = __DIR__ . "/demo.db";

// --- 1. データベースに接続 ---
echo "=== 1. データベースに接続 ===" . PHP_EOL;

try {
    // SQLite ファイルに接続（なければ自動作成）
    $pdo = new PDO("sqlite:" . $dbPath);

    // エラー時に例外を投げる設定（デバッグに便利）
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "データベースに接続しました: demo.db" . PHP_EOL;
} catch (PDOException $e) {
    echo "接続エラー: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
echo PHP_EOL;

// --- 2. テーブルの作成（CREATE TABLE） ---
echo "=== 2. テーブルの作成 ===" . PHP_EOL;

$pdo->exec("DROP TABLE IF EXISTS todos");  // デモ用にリセット

$pdo->exec("
    CREATE TABLE todos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        is_done INTEGER DEFAULT 0,
        created_at TEXT DEFAULT (datetime('now', 'localtime'))
    )
");
echo "todosテーブルを作成しました" . PHP_EOL;
echo "  カラム: id, title, is_done, created_at" . PHP_EOL;
echo PHP_EOL;

// --- 3. データの追加（INSERT） ---
echo "=== 3. データの追加（INSERT） ===" . PHP_EOL;

// プリペアドステートメント: SQLインジェクション対策として必須！
// ? の部分に値を安全に埋め込める
$stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (?)");

$tasks = ["PHPの基礎を学ぶ", "クラスを理解する", "DBの使い方を覚える", "Webアプリを作る"];
foreach ($tasks as $task) {
    $stmt->execute([$task]);
    echo "  追加: " . $task . "（ID: " . $pdo->lastInsertId() . "）" . PHP_EOL;
}
echo PHP_EOL;

// --- 4. データの取得（SELECT） ---
echo "=== 4. データの取得（SELECT） ===" . PHP_EOL;

// 全件取得
echo "[全件取得]" . PHP_EOL;
$stmt = $pdo->query("SELECT * FROM todos");
$allTodos = $stmt->fetchAll(PDO::FETCH_ASSOC);  // 連想配列で取得

foreach ($allTodos as $todo) {
    $status = $todo["is_done"] ? "[完了]" : "[未完了]";
    echo "  " . $todo["id"] . ". " . $status . " " . $todo["title"] . PHP_EOL;
}
echo PHP_EOL;

// 条件付き取得（WHERE）
echo "[条件付き取得] タイトルに「学ぶ」を含むもの:" . PHP_EOL;
$stmt = $pdo->prepare("SELECT * FROM todos WHERE title LIKE ?");
$stmt->execute(["%学ぶ%"]);

while ($todo = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  " . $todo["id"] . ". " . $todo["title"] . PHP_EOL;
}
echo PHP_EOL;

// --- 5. データの更新（UPDATE） ---
echo "=== 5. データの更新（UPDATE） ===" . PHP_EOL;

// ID 1 と 2 を完了にする
$stmt = $pdo->prepare("UPDATE todos SET is_done = 1 WHERE id = ?");
$stmt->execute([1]);
echo "  ID 1 を完了にしました" . PHP_EOL;
$stmt->execute([2]);
echo "  ID 2 を完了にしました" . PHP_EOL;

// 更新後の一覧
$stmt = $pdo->query("SELECT * FROM todos");
echo "[更新後の一覧]" . PHP_EOL;
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $todo) {
    $status = $todo["is_done"] ? "[完了]  " : "[未完了]";
    echo "  " . $todo["id"] . ". " . $status . " " . $todo["title"] . PHP_EOL;
}
echo PHP_EOL;

// --- 6. データの削除（DELETE） ---
echo "=== 6. データの削除（DELETE） ===" . PHP_EOL;

// 完了済みのタスクを削除
$stmt = $pdo->prepare("DELETE FROM todos WHERE is_done = ?");
$stmt->execute([1]);
echo "  完了済みタスクを削除しました" . PHP_EOL;

$stmt = $pdo->query("SELECT * FROM todos");
echo "[削除後の一覧]" . PHP_EOL;
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $todo) {
    echo "  " . $todo["id"] . ". " . $todo["title"] . PHP_EOL;
}
echo PHP_EOL;

// --- 7. 集計（COUNT） ---
echo "=== 7. 集計 ===" . PHP_EOL;

$stmt = $pdo->query("SELECT COUNT(*) as total FROM todos");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "  残りのタスク数: " . $result["total"] . "件" . PHP_EOL;
echo PHP_EOL;

// --- 8. トランザクション ---
echo "=== 8. トランザクション ===" . PHP_EOL;
echo "  ※ 複数の操作を「全部成功」か「全部取り消し」にする仕組み" . PHP_EOL;

try {
    $pdo->beginTransaction();  // トランザクション開始

    $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (?)");
    $stmt->execute(["トランザクションのテスト1"]);
    $stmt->execute(["トランザクションのテスト2"]);

    $pdo->commit();  // 全部成功 → 確定
    echo "  → コミット成功（2件追加）" . PHP_EOL;
} catch (Exception $e) {
    $pdo->rollBack();  // エラー → 全部取り消し
    echo "  → ロールバック: " . $e->getMessage() . PHP_EOL;
}
echo PHP_EOL;

// --- 9. 後片付け ---
echo "=== 9. 後片付け ===" . PHP_EOL;
$pdo = null;  // 接続を閉じる
unlink($dbPath);  // DBファイルを削除
echo "データベースファイルを削除しました" . PHP_EOL;
echo PHP_EOL;

// --- まとめ ---
echo "=== まとめ ===" . PHP_EOL;
echo "  PDO    → PHP でDBを扱う標準ライブラリ" . PHP_EOL;
echo "  SQLite → ファイル1つで動く手軽なDB" . PHP_EOL;
echo "  CRUD   → Create(INSERT), Read(SELECT), Update(UPDATE), Delete(DELETE)" . PHP_EOL;
echo "  prepare → SQLインジェクション対策に必須！値は必ずプレースホルダで" . PHP_EOL;
