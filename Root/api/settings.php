<?php

header('Content-Type: application/json');
require_once __DIR__ . '/includes/settings_handler.php';

session_start();
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$handler = new SettingsHandler();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        try {
            $settings = $handler->getSettings($userId);
            echo json_encode($settings);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve settings']);
        }
        break;

    case 'POST':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data');
            }
            
            $success = $handler->saveSettings($userId, $data);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}