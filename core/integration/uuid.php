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
    private string $username;
    private string $uuid;
    private array $properties;

    /**
     * @param string $username The player's username.
     * @param string $uuid The player's UUID.
     * @param array $properties The player's properties specified on their Mojang profile.
     */
    function __construct(string $username, string $uuid, array $properties = []) {
        $this->username = $username;
        $this->uuid = $uuid;
        $this->properties = $properties;
    }

    /**
     * @return string The player's username.
     */
    public function getUsername(): string{
        return $this->username;
    }

    /**
     * @return string The player's UUID.
     */
    public function getUUID(): string {
        return $this->uuid;
    }

    /**
     * @return array The player's properties listed on their mojang profile.
     */
    public function getProperties(): array {
        return $this->properties;
    }

    /**
     * @return array Returns an array with keys of 'properties, usernname and uuid'.
     */
    public function getProfileAsArray(): array {
        return ['username' => $this->username, 'uuid' => $this->uuid, 'properties' => $this->properties];
    }
}

class ProfileUtils {
    /**
     * @param string $identifier Either the player's Username or UUID.
     * @return MinecraftProfile|null Returns null if fetching of profile failed. Else returns completed user profile.
     */
    public static function getProfile(string $identifier): ?MinecraftProfile {
        if(strlen($identifier) <= 16){
            $identifier = ProfileUtils::getUUIDFromUsername($identifier);
            $url = 'https://sessionserver.mojang.com/session/minecraft/profile/' .$identifier['uuid'];
        } else {
            $url = 'https://sessionserver.mojang.com/session/minecraft/profile/' . $identifier;
        }

        $ret = HttpClient::get($url);

        if (!$ret->hasError()) {
            $data = json_decode($ret->data(), true);
            return new MinecraftProfile($data['name'], $data['id'], $data['properties']);
        } else {
            return null;
        }
    }

    /**
     * @param $username string Minecraft username.
     * @return array (Key => Value) "username" => Minecraft username (properly capitalized) "uuid" => Minecraft UUID
     */
    public static function getUUIDFromUsername(string $username): ?array {
        if(strlen($username) > 16)
            return ['username' => '', 'uuid' => ''];
        $url = 'https://api.mojang.com/users/profiles/minecraft/'.htmlspecialchars($username);

        $result = HttpClient::get($url);

        // Verification
        if (!$result->hasError()) {
            $ress = json_decode($result->data(), true);
            return ['username' =>  $ress['name'], 'uuid' => $ress['id']];
        } else {
            return null;
        }
    }

    /**
    * @param string $uuid UUID to format
    * @return string Properly formatted UUID (According to UUID v4 Standards xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx WHERE y = 8,9,A,or B and x = random digits.)
    */
    public static function formatUUID(string $uuid): string {
        $uid = substr($uuid, 0, 8) . '-';
        $uid .= substr($uuid, 8, 4). '-';
        $uid .= substr($uuid, 12, 4). '-';
        $uid .= substr($uuid, 16, 4). '-';
        $uid .= substr($uuid, 20);
        return $uid;
    }
}
