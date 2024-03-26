<?php

namespace api;

use \utils\Utils;

class Api {
    public static $access_token, $user_id;

    public const CHAT_PEER_ID = 2000000000;

    public static function callMethod($method, $params = []) {
        $params['access_token'] = self::$access_token;
        $params['v'] = '5.131';
        return Utils::requestURL('https://api.vk.com/method/'.$method.'?'.http_build_query($params));
    }
    public static function reply($message, $update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        $params['message'] = $message;
        $params['forward'] = json_encode([
            'peer_id' => $update['peer_id'],
            'is_reply' => true,
            'message_ids' => $update['message_id'],
        ], JSON_UNESCAPED_UNICODE);
        $params['random_id'] = 0;
        return self::callMethod('messages.send', $params);
    }
    public static function sendMessage($message, $update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        $params['message'] = $message;
        $params['random_id'] = 0;
        return self::callMethod('messages.send', $params);
    }
    public static function editMessage($message, $update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        $params['message'] = $message;
        $params['message_id'] = $update['message_id'];
        $params['random_id'] = 0;
        return self::callMethod('messages.edit', $params);
    }
    public static function deleteMessage($update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        $params['delete_for_all'] = true;
        $params['message_ids'] = $update['message_id'];
        $params['random_id'] = 0;
        return self::callMethod('messages.delete', $params);
    }
    public static function sendReactionByUpdate($reaction_id, $update, $params = []) {
        $messageById = self::callMethod('messages.getById', [
            'message_ids' => $update['message_id']
        ]);
        if (!isset($messageById['response']['items'][0])) {
            return;
        }
        $params['peer_id'] = $update['peer_id'];
        $params['cmid'] = $messageById['response']['items'][0]['conversation_message_id'];
        $params['reaction_id'] = $reaction_id;
        $params['random_id'] = 0;
        return self::callMethod('messages.sendReaction', $params);
    }
    public static function sendReaction($reaction_id, $peer_id, $conversation_message_id, $params = []) {
        $params['peer_id'] = $peer_id;
        $params['cmid'] = $conversation_message_id;
        $params['reaction_id'] = $reaction_id;
        $params['random_id'] = 0;
        return self::callMethod('messages.sendReaction', $params);
    }
    public static function getChatMembers($update, $params = []) {
        $params['peer_id'] = $update['peer_id'];
        return self::callMethod('messages.getConversationMembers', $params);
    }
    public static function getUser($user_id, $params = []) {
        $params['user_id'] = $user_id;
        return self::callMethod('users.get', $params)['response'][0];
    }
}
