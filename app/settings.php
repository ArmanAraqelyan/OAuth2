<?php
declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'db' => [
                    'db',               //host
                    'oauth2',           //database
                    'root',             //user
                    'example'           //pass
                ],
                'dbClass'       => \App\DataBase\MySQL\MySQLDataBase::class,
                'privateKey'    => 'file://' . __DIR__ . '/../private.key',
                'publicKey'    => 'file://' . __DIR__ . '/../public.key',
                'encryptionKey' => 'dSgVkYp3s6v9y$B&E)H+MbQeThWmZq4t7w!z%C*F-JaNcRfUjXn2r5u8x/A?D(G+',
            ]);
        }
    ]);
};
