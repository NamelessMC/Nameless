<?php
/**
 * Allows modules to define permissions.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class PermissionCache
{
    private DB $_db;
    private Cache $_cache;

    public function __construct(DB $db, Cache $cache)
    {
        $this->_db = $db;
        $this->_cache = $cache;
    }

    public function getOrLoad(string $permissible, int $id): array
    {
        $this->_cache->setCache('permission_cache');

        if ($this->_cache->isCached($cache_key = $this->cacheKey($permissible, $id))) {
            return $this->_cache->retrieve($cache_key);
        }

        $this->load($permissible, $id);

        return $this->_cache->retrieve($cache_key);
    }

    public function flush(string $permissible = null, int $id = null): void
    {
        $this->_cache->setCache('permission_cache');

        if ($permissible === null || $id === null) {
            $this->_cache->eraseAll();
            return;
        }

        if ($this->_cache->isCached($cache_key = $this->cacheKey($permissible, $id))) {
            $this->_cache->erase($cache_key);
        }
    }

    public function upsert(string $permissible, int $id, array $permissions): void
    {
        if (!count($permissions)) {
            return;
        }

        $values_sql = '';
        $values = [];
        foreach ($permissions as $permission => $value) {
            if (!in_array($value, [PermissionTristate::TRUE, PermissionTristate::FALSE, PermissionTristate::INHERIT])) {
                continue;
            }

            $values_sql .= '(?, ?, ?, ?), ';
            $values[] = "$permissible";
            $values[] = $id;
            $values[] = "$permission";
            $values[] = $value;
        }

        $this->_db->query(
            'INSERT INTO nl2_permissions (permissible, permissible_id, permission, `value`) VALUES ' . rtrim($values_sql, ', ') . ' ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
            $values,
        );

        $this->flush($permissible, $id);
    }

    private function load(string $permissible, int $id): array
    {        
        $permissions = $this->_db->query(
            'SELECT permission, `value` FROM nl2_permissions WHERE permissible = ? AND permissible_id = ?', [$permissible, $id]
        )->results();

        $loaded_permissions = [];
        foreach ($permissions as $permission) {
            $loaded_permissions[$permission->permission] = $permission->value;
        }

        $this->_cache->setCache('permission_cache');
        $this->_cache->store($this->cacheKey($permissible, $id), $loaded_permissions);

        return $loaded_permissions;
    }

    private function cacheKey(string $permissible, int $id): string
    {
        return $permissible . '_' . $id;
    }
}