<?php
session_start();

define('STEAM_API_KEY', 'AC5D04FA890C7B417618838B8034C312');
define('STEAM_API_URL', 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/');

function validateSteamLogin() {
    $params = array(
        'openid.assoc_handle' => $_GET['openid_assoc_handle'],
        'openid.signed' => $_GET['openid_signed'],
        'openid.sig' => $_GET['openid_sig'],
        'openid.ns' => 'http://specs.openid.net/auth/2.0',
        'openid.mode' => 'check_authentication'
    );
    
    $signed = explode(',', $_GET['openid_signed']);
    foreach($signed as $item) {
        $val = $_GET['openid_' . str_replace('.', '_', $item)];
        $params['openid.' . $item] = $val;
    }
    
    $data = http_build_query($params);
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                       "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
        )
    ));
    
    $result = file_get_contents('https://steamcommunity.com/openid/login', false, $context);
    return preg_match("/is_valid\s*:\s*true/i", $result);
}

function getSteamID($openid_url) {
    return basename($openid_url);
}

function getSteamUserInfo($steam_id) {
    $url = STEAM_API_URL . '?key=' . STEAM_API_KEY . '&steamids=' . $steam_id;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (isset($data['response']['players'][0])) {
        return $data['response']['players'][0];
    }
    return null;
}

if (validateSteamLogin()) {
    $steam_id = getSteamID($_GET['openid_identity']);
    $user_info = getSteamUserInfo($steam_id);
    
    if ($user_info) {
        $_SESSION['steam_id'] = $steam_id;
        $_SESSION['steam_username'] = $user_info['personaname'];
        $_SESSION['steam_avatar'] = $user_info['avatarmedium'];
        $_SESSION['steam_profile_url'] = $user_info['profileurl'];
        
        header('Location: ../index.html?login=success');
        exit();
    }
}

header('Location: ../index.html?login=error');
exit();
?>
