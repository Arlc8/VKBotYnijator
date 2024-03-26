<?php

namespace api;

use \api\Api;

/**
 * Class Docs
 */
class Docs {
    /**
     * @param string $type
     * @param $peerId
     * @return string
     */
    public static function getUploadServer(string $type, $peerId): string {
        return Api::callMethod('docs.getMessagesUploadServer', [
            'peer_id' => $peerId,
            'type' => $type
        ])['response']['upload_url'];
    }

    /**
     * @param string $path
     * @param string $type
     * @param $peerId
     * @return array|false
     */
    public static function getUrlDoc(string $path, string $type, $peerId) {
        if (!class_exists('CURLFile', false)) return false;
        if (!file_exists($path)) return false;

        $url = self::getUploadServer($type, $peerId);

        $myCurl = curl_init();
        curl_setopt_array($myCurl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data',
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'file' => new \CURLFile($path),
            ]
        ]);
        $response = json_decode(curl_exec($myCurl), 1);

        return Api::callMethod('docs.save', [
            'file' => $response['file']
        ]);
    }
}
