<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: connection.class.php

class pConnection extends PDO
{
    protected int $queryCount = 0;
    protected array $internCache = [];
    protected array $internLog = [];

    public function __construct(string $dsn, string $user, string $password, array $options = [])
    {
        parent::__construct($dsn, $user, $password, $options);
        
        $this->exec("SET NAMES UTF8");
        $this->query("SET CHARACTER_SET_RESULTS='UTF8'");
        $this->initConfig();
    }

    protected function initConfig(): void
    {
        $settings = $this->query("SELECT * FROM config");
        if ($settings) {
            while ($setting = $settings->fetchObject()) {
                $constantName = "CONFIG_" . $setting->SETTING_NAME;
                if (!defined($constantName)) {
                    define($constantName, $setting->SETTING_VALUE);
                }
            }
        }
    }

    public function showCount(): int
    {
        return $this->queryCount;
    }

    public function log(): array
    {
        return $this->internLog;
    }

    public function cacheQuery(string $sql, bool $force_no_cache = false, bool $force_no_count = false): mixed
    {
        $trace = debug_backtrace();
        $functions = [];
        foreach ($trace as $traceIns) {
            $functions[] = ($traceIns['class'] ?? '') . '::' . ($traceIns['function'] ?? '') . ':-' . ($traceIns['file'] ?? '') . ':' . ($traceIns['line'] ?? '');
        }

        $this->internLog[] = [$functions, $sql];
        $this->queryCount++;

        $tableName = '';
        if (preg_match_all('/\b(FROM|INTO|UPDATE)\b\s*(\w+)/i', $sql, $matches)) {
            $tableName = $matches[2][0] ?? '';
        }

        if ($force_no_cache || (defined('CONFIG_ENABLE_QUERY_CACHING') && CONFIG_ENABLE_QUERY_CACHING == 0)) {
            if (!p::StartsWith($sql, "SELECT")) {
                return $this->query($sql);
            }
            $execute = $this->query($sql);
            $objects = [];
            if ($execute) {
                while ($object = $execute->fetchObject()) {
                    $objects[] = $object;
                }
                return new pCachedQuery($objects, $execute->rowCount(), $sql);
            }
            return false;
        }

        if (!p::StartsWith($sql, "SELECT")) {
            $this->CleanCache('queries', $tableName ? $tableName . '_' : '');
            return $this->query($sql);
        }

        if (!$this->shouldCacheQuery($sql)) {
            return $this->query($sql);
        }

        $hash = $tableName ? $tableName . '_' . md5($sql) : md5($sql);
        $cacheFile = p::FromRoot('cache/queries/' . $hash . '.cache');
        $cacheFolder = p::FromRoot('cache/queries');
        $cacheTime = defined('CONFIG_QC_TIME') ? CONFIG_QC_TIME : 3600;

        if (!is_writable($cacheFolder)) {
            return $this->query($sql);
        }

        if (file_exists($cacheFile)) {
            $cacheFileTime = filemtime($cacheFile);

            if (time() - $cacheFileTime < $cacheTime) {
                $content = file_get_contents($cacheFile);
                if ($content !== false) {
                    $unserialized = unserialize($content);
                    if ($unserialized !== false) {
                        return $unserialized;
                    }
                }
            }

            @unlink($cacheFile);
            return $this->cacheQuery($sql, true, true);
        }

        $execute = $this->query($sql);

        if (!$execute || $execute->rowCount() == 0) {
            return $execute;
        }

        $objects = [];
        while ($object = $execute->fetchObject()) {
            $objects[] = $object;
        }

        $cacheQuery = new pCachedQuery($objects, $execute->rowCount(), $sql);

        if (!file_put_contents($cacheFile, serialize($cacheQuery))) {
            return $this->cacheQuery($sql, true);
        }

        return $cacheQuery;
    }

    private function shouldCacheQuery(string $sql): bool
    {
        preg_match_all('/\b(FROM|INTO|UPDATE|DELETE|JOIN)\s+(\w+)/i', $sql, $matches);
        $targetTables = array_unique($matches[2] ?? []);

        return !in_array('log', $targetTables, true) &&
               !in_array('users', $targetTables, true) &&
               !in_array('user_activation', $targetTables, true);
               !in_array('bans', $targetTables, true);
    }

    public function CleanCache(string $section = 'queries', string $prefix = ''): bool
    {
        $rootPath = defined('CONFIG_ROOT_PATH') ? CONFIG_ROOT_PATH : '';
        $pattern = $rootPath . '/cache/' . $section . '/' . $prefix . '*.cache';
        
        $files = glob($pattern);
        if ($files !== false) {
            foreach ($files as $filename) {
                @unlink($filename);
            }
        }

        return true;
    }
}