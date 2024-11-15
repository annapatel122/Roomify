<?php

class DatabaseConnection {
    private static $mongoClient = null;
    private static $database = null;
    
    public static function getDatabase() {
        if (self::$database === null) {
            try {
                // MongoDB connection
                $mongoUri = sprintf(
                    "mongodb://%s:%s@%s:%s",
                    getenv('MONGO_USER'),
                    getenv('MONGO_PASS'),
                    getenv('MONGO_HOST'),
                    getenv('MONGO_PORT')
                );
                
                self::$mongoClient = new MongoDB\Client($mongoUri);
                self::$database = self::$mongoClient->selectDatabase(getenv('MONGO_DB'));
                
            } catch (MongoDB\Driver\Exception\Exception $e) {
                error_log("MongoDB Connection Error: " . $e->getMessage());
                throw $e;
            }
        }
        return self::$database;
    }
}