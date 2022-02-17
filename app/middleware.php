<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Psr\Http\Server\RequestHandlerInterface;

return function (App $app) {
    $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app) {
        $settings = $app->getContainer()->get(SettingsInterface::class);

        $request->withHeader("Access-Control-Allow-Origin", "*");
        $request->withHeader("Access-Control-Allow-Headers", "*");
        $request->withHeader("Access-Control-Allow-Credentials", 'true');
        
        chmod($settings->get('privateKey'), 0660);
        chmod($settings->get('publicKey'), 0660);
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $request = $request->withParsedBody($data);

        return $handler->handle($request);
    });
};
