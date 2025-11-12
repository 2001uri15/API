<?php

header('Content-Type: application/json');
$target = __DIR__ . '/../app/login/register_action.php';
if (!file_exists($target)) {
    echo json_encode(['success' => false, 'message' => 'Handler not found']);
    http_response_code(500);
    exit;
}
require_once $target;

?>
