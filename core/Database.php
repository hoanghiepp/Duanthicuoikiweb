<?php
declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection) return self::$connection;
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_DATABASE, DB_CHARSET);
        try {
            self::$connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo '<h1>Không kết nối được database</h1>';
            echo '<p>Hãy import <b>database/hr_management.sql</b> và kiểm tra <b>config/database.php</b>.</p>';
            echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
            exit;
        }
        return self::$connection;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row ?: null;
    }

    public static function all(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }
}
