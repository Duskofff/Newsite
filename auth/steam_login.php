<?php
session_start();

define('STEAM_API_KEY', 'AC5D04FA890C7B417618838B8034C312');
define('STEAM_LOGIN_URL', 'https://steamcommunity.com/openid/login');

$return_url = 'http://new-dawn.byethost8.com/auth/steam_callback.php';

function getSteamLoginUrl($return_url) {
    $params = array(
        'openid.ns' => 'http://specs.openid.net/auth/2.0',
        'openid.mode' => 'checkid_setup',
        'openid.return_to' => $return_url,
        'openid.realm' => 'http://' . $_SERVER['HTTP_HOST'],
        'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select'
    );
    
    return STEAM_LOGIN_URL . '?' . http_build_query($params);
}

if (!isset($_SESSION['steam_id'])) {
    $steam_login_url = getSteamLoginUrl($return_url);
    header('Location: ' . $steam_login_url);
    exit();
} else {
    header('Location: ../index.html');
    exit();
}
?>
