<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['steam_id'])) {
    echo json_encode([
        'logged_in' => true,
        'steam_id' => $_SESSION['steam_id'],
        'username' => $_SESSION['steam_username'],
        'avatar' => $_SESSION['steam_avatar'],
        'profile_url' => $_SESSION['steam_profile_url']
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>
