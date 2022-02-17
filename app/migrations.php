<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use Slim\App;

return function (App $app) {
    /** @var SettingsInterface $settings */
    $settings = $app->getContainer()->get(SettingsInterface::class);
    list($host, $dbname, $user, $pass) = $settings->get('db');

    $PDO = new PDO('mysql:host=' . $host . ';dbname=' . $dbname,
            $user, $pass);
        
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $PDO->query(
        "CREATE TABLE IF NOT EXISTS scope (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            PRIMARY KEY (id)
        )"
    );
    
    $PDO->query(
        "CREATE TABLE IF NOT EXISTS user (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL UNIQUE,
            mail VARCHAR(255) NOT NULL UNIQUE,
            pass VARCHAR(255) NOT NULL,
            reg_date INT NOT NULL,
            PRIMARY KEY (id)
        )"
    );

    $PDO->query(
        "CREATE TABLE IF NOT EXISTS client (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            uri TEXT NOT NULL,
            secret VARCHAR(255) NOT NULL,
            confidential BOOL NOT NULL DEFAULT TRUE,
            PRIMARY KEY (id)
        )"
    );
    
    $PDO->query(
        "CREATE TABLE IF NOT EXISTS auth_code (
            id INT NOT NULL AUTO_INCREMENT,
            identifier VARCHAR(255) NOT NULL UNIQUE,
            user_id INT NOT NULL,
            client_id INT NOT NULL,
            scope TEXT NOT NULL,
            expiry_time INT NOT NULL,
            revoked BOOL NOT NULL DEFAULT FALSE,
            revoke_date INT,
            FOREIGN KEY (user_id)
                REFERENCES user (id),
            FOREIGN KEY (client_id)
                REFERENCES client (id),
            PRIMARY KEY (id)
        )"
    );

    $PDO->query(
        "CREATE TABLE IF NOT EXISTS token (
            id INT NOT NULL AUTO_INCREMENT,
            identifier VARCHAR(255) NOT NULL UNIQUE,
            user_id INT NOT NULL,
            client_id INT NOT NULL,
            scope TEXT NOT NULL,
            expiry_time INT NOT NULL,
            revoked BOOL NOT NULL DEFAULT FALSE,
            revoke_date INT,
            FOREIGN KEY (user_id)
                REFERENCES user (id),
            FOREIGN KEY (client_id)
                REFERENCES client (id),
            PRIMARY KEY (id)
        )"
    );

    $PDO->query(
        "CREATE TABLE IF NOT EXISTS refresh_token (
            id INT NOT NULL AUTO_INCREMENT,
            identifier VARCHAR(255) NOT NULL UNIQUE,
            access_token VARCHAR(255) NOT NULL,
            expiry_time INT NOT NULL,
            revoked BOOL NOT NULL DEFAULT FALSE,
            revoke_date INT,
            PRIMARY KEY (id)
        )"
    );
};