<?php
/**
 * Provides methods to generate a MinecraftProfile from a username or UUID.
 *
 * @package NamelessMC\Minecraft
 * @see MinecraftProfile
 * @author Daniel Fanara
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class ProfileUtils {

    /**
     * Get a MinecraftProfile from a username or UUID.
     *
     * @param string $identifier Either the player's Username or UUID.
     * @return MinecraftProfile|null Returns null if fetching of profile failed. Else returns completed user profile.
     */
    public static function getProfile(string $identifier): ?MinecraftProfile {
        if (strlen($identifier) <= 16) {
            $result = self::getUUIDFromUsername($identifier);
            if ($result === null) {
                return null;
            }
            $uuid = $result['uuid'];
        } else {
            $uuid = $identifier;
        }

        $client = HttpClient::get('https://sessionserver.mojang.com/session/minecraft/profile/' . $uuid);

        if (!$client->hasError()) {
            $data = $client->json(true);
            return new MinecraftProfile($data['name'], $data['id'], $data['properties']);
        }

        return null;
    }

    /**
     * Get a Minecraft UUID from a Minecraft username.
     *
     * @param string $username Minecraft username.
     * @return array (Key => Value) "username" => Minecraft username (properly capitalized) "uuid" => Minecraft UUID or null
     */
    private static function getUUIDFromUsername(string $username): ?array {
        if (strlen($username) > 16) {
            return ['username' => '', 'uuid' => ''];
        }

        $result = HttpClient::get('https://api.mojang.com/users/profiles/minecraft/' . urlencode($username));

        // Verification, API will return 204 status code if username is invalid
        if (!$result->hasError() && $result->getStatus() === 200) {
            $ress = json_decode($result->contents(), true);
            if ($ress['name'] != null && $ress['id'] != null) {
                return [
                    'username' => $ress['name'],
                    'uuid' => $ress['id']
                ];
            }
        }

        return null;
    }

    /**
     * Generate an offline minecraft UUID v3 based on the case sensitive player name.
     *
     * @param string $username
     * @return array
     */
    public static function getOfflineModeUuid(string $username): array {
        $data = hex2bin(md5("OfflinePlayer:" . $username));
        $data[6] = chr(ord($data[6]) & 0x0f | 0x30);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return [
            'uuid' => bin2hex($data),
            'username' => $username
        ];
    }

    /**
    * Add dashes to UUID
    *
    * @param string $uuid string UUID to format
    * @return string Properly formatted UUID (According to UUID v4 Standards xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx WHERE y = 8,9,A,or B and x = random digits.)
    */
    public static function formatUUID(string $uuid): string {
        $uid = "";
        $uid .= substr($uuid, 0, 8)."-";
        $uid .= substr($uuid, 8, 4)."-";
        $uid .= substr($uuid, 12, 4)."-";
        $uid .= substr($uuid, 16, 4)."-";
        $uid .= substr($uuid, 20);
        return $uid;
    }
}
