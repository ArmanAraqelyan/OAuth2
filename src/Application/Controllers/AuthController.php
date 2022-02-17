<?php
namespace App\Application\Controllers;

use App\Application\Settings\SettingsInterface;
use App\Entities\UserEntity;
use App\Repositories\AccessTokenRepository;
use App\Repositories\AuthCodeRepository;
use App\Repositories\ClientRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\ScopeRepository;
use App\Repositories\UserRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

class AuthController {

    /** @var ContainerInterface $app */
    private $container;

    /** @var AuthorizationServer $server */
    private $server;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $server = new \League\OAuth2\Server\AuthorizationServer(
            $container->get(ClientRepository::class),
            $container->get(AccessTokenRepository::class),
            $container->get(ScopeRepository::class),
            $container->get(SettingsInterface::class)->get('privateKey'),
            $container->get(SettingsInterface::class)->get('encryptionKey')
        );
        
        $grant = new \League\OAuth2\Server\Grant\AuthCodeGrant(
            $container->get(AuthCodeRepository::class),
             $container->get(RefreshTokenRepository::class),
             new \DateInterval('PT10M')
        );
        
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        
        $server->enableGrantType(
            $grant,
            new \DateInterval('PT1H')
        );

        $this->server = $server;
    }

    public function get_auth_code(Request $request, Response $response): Response
    {
        $log_pass = $request->getHeader('Authorization')[0];
        $log_pass = base64_decode(str_replace('Basic ', '', $log_pass));
        $log_pass = explode(':', $log_pass);

        /** @var UserEntity $user */
        $user = $this->container->get(UserRepository::class)->login($log_pass[0], $log_pass[1]);
        try {
            $authRequest = $this->server->validateAuthorizationRequest($request);
            $authRequest->setUser($user);
            $authRequest->setAuthorizationApproved(true);

            return $this->server->completeAuthorizationRequest($authRequest, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());

            return $response->withStatus(500)->withBody($body);
        }
    }

    public function get_auth_token(Request $request, Response $response): Response
    {
        try {
            return $this->server->respondToAccessTokenRequest($request, $response);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());

            return $response->withStatus(500)->withBody($body);
        }
    }
}
