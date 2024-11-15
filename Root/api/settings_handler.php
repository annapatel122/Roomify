<?php

class SettingsHandler {
    private $conn;
    
    public function __construct() {
        $this->conn = new PDO(
            "mysql:host=localhost;dbname=roomify_db",
            "root",
            ""
        );
    }
    
    public function saveSettings($userId, $settings) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO user_settings 
                (user_id, setting_key, setting_value) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");
            
            foreach ($settings as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $stmt->execute([$userId, $key, $value]);
            }
            return true;
        } catch (PDOException $e) {
            error_log("MySQL Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getSettings($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT setting_key, setting_value 
                FROM user_settings 
                WHERE user_id = ?
            ");
            
            $stmt->execute([$userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $defaultSettings = [
                'notifications' => [
                    'email' => false,
                    'messages' => true,
                    'matches' => true
                ],
                'privacy' => [
                    'publicProfile' => true,
                    'showOnline' => true
                ],
                'theme' => 'light'
            ];
            
            if (empty($results)) {
                return $defaultSettings;
            }
            
            $settings = [];
            foreach ($results as $row) {
                $value = $row['setting_value'];
                if ($this->isJson($value)) {
                    $value = json_decode($value, true);
                }
                $settings[$row['setting_key']] = $value;
            }
            
            return $settings;
        } catch (PDOException $e) {
            error_log("MySQL Error: " . $e->getMessage());
            return null;
        }
    }
    
    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}