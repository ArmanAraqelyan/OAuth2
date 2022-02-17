<?php
declare(strict_types=1);

use App\DataBase\AbstractDataBase;
use App\Repositories\AccessTokenRepository;
use App\Repositories\AuthCodeRepository;
use App\Repositories\ClientRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\ScopeRepository;
use App\Repositories\UserRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => function (ContainerInterface $c) {
            $db = $c->get(AbstractDataBase::class);
            return new UserRepository($db);
        },
        ClientRepository::class => function (ContainerInterface $c) {
            $db = $c->get(AbstractDataBase::class);
            return new ClientRepository($db);
        },
        ScopeRepository::class => function (ContainerInterface $c) {
            $db = $c->get(AbstractDataBase::class);
            return new ScopeRepository($db);
        },
        AccessTokenRepository::class => function (ContainerInterface $c) {
            $db = $c->get(AbstractDataBase::class);
            return new AccessTokenRepository($db);
        },
        AuthCodeRepository::class => function (ContainerInterface $c) {
            $db = $c->get(AbstractDataBase::class);
            return new AuthCodeRepository($db);
        },
        RefreshTokenRepository::class => function (ContainerInterface $c) {
            $db = $c->get(AbstractDataBase::class);
            return new RefreshTokenRepository($db);
        },
    ]);
};