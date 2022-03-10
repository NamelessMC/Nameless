<?php
/*
 * The MIT License (MIT)
 * Copyright (c) 2014 Daniel Fanara
 * https://github.com/Shadowwolf97/Minecraft-UUID-Utils
 *
 * Modified by Samerton for NamelessMC
 * https://github.com/NamelessMC/Nameless
 */

class MinecraftProfile {

    private string $_username;
    private string $_uuid;
    private array $_properties;

    /**
     * @param string $username The player's username.
     * @param string $uuid The player's UUID.
     * @param array $properties The player's properties specified on their Mojang profile.
     */
    public function __construct(string $username, string $uuid, array $properties = []) {
        $this->_username = $username;
        $this->_uuid = $uuid;
        $this->_properties = $properties;
    }

    /**
     * @return string The player's username.
     */
    public function getUsername(): string {
        return $this->_username;
    }

    /**
     * @return string The player's UUID.
     */
    public function getUUID(): string {
        return $this->_uuid;
    }

    /**
     * @return array The player's properties listed on their mojang profile.
     */
    public function getProperties(): array {
        return $this->_properties;
    }

    /**
     * @return array Returns an array with keys of 'properties, usernname and uuid'.
     */
    public function getProfileAsArray(): array {
        return [
            'username' => $this->_username,
            'uuid' => $this->_uuid,
            'properties' => $this->_properties
        ];
    }
}

class ProfileUtils {
    /**
     * @param string $identifier Either the player's Username or UUID.
     * @return MinecraftProfile|null Returns null if fetching of profile failed. Else returns completed user profile.
     */
    public static function getProfile(string $identifier): ?MinecraftProfile {
        if (strlen($identifier) <= 16) {
            var_dump($identifier);
            $result = self::getUUIDFromUsername($identifier);
            if ($result == null) {
                return null;
            }
            $uuid = $result['uuid'];
        } else {
            $uuid = $identifier;
        }

        $url = 'https://sessionserver.mojang.com/session/minecraft/profile/' . $uuid;

        $client = HttpClient::get($url);

        if (!$client->hasError()) {
            $data = $client->json(true);
            return new MinecraftProfile($data['name'], $data['id'], $data['properties']);
        }

        return null;
    }

    /**
     * @param string $username Minecraft username.
     * @return array (Key => Value) "username" => Minecraft username (properly capitalized) "uuid" => Minecraft UUID
     */
    public static function getUUIDFromUsername(string $username): ?array {
        if (strlen($username) > 16) {
            return ['username' => '', 'uuid' => ''];
        }
        $url = 'https://api.mojang.com/users/profiles/minecraft/' . urlencode($username);

        $result = HttpClient::get($url);

        // Verification
        if (!$result->hasError()) {
            $ress = json_decode($result->contents(), true);
            if ($ress['name'] != null && $ress['uuid'] != null) {
                return [
                    'username' => $ress['name'],
                    'uuid' => $ress['id']
                ];
            }
        }

        return null;
    }

    /**
     * @param string $uuid UUID to format
     * @return string Properly formatted UUID (According to UUID v4 Standards xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx WHERE y = 8,9,A,or B and x = random digits.)
     */
    public static function formatUUID(string $uuid): string {
        $uid = substr($uuid, 0, 8) . '-';
        $uid .= substr($uuid, 8, 4) . '-';
        $uid .= substr($uuid, 12, 4) . '-';
        $uid .= substr($uuid, 16, 4) . '-';
        $uid .= substr($uuid, 20);
        return $uid;
    }
}
