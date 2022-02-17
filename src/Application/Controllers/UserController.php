<?php
namespace App\Application\Controllers;

use App\Application\Settings\SettingsInterface;
use App\Entities\UserEntity;
use App\Repositories\AccessTokenRepository;
use App\Repositories\UserRepository;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController {

    /** @var ContainerInterface $app */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registration(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $name       = $data['name'];
        $email      = $data['email'];
        $password   = $data['password'];

        try {
            /** @var UserRepository $repo */
            $repo = $this->container->get(UserRepository::class);
            $repo->register($name, $email, $password);
            $response->getBody()->write('Registered successfully');
        }catch (Exception $ex) {
            $response->withStatus(500)->getBody()->write($ex->getMessage());
        }

        return $response;
    }

    public function get_current_user(Request $request, Response $response): Response
    {
        $pubKey = $this->container->get(SettingsInterface::class)->get('publicKey');
        $accessToken = $this->container->get(AccessTokenRepository::class);

        $server = new ResourceServer($accessToken, $pubKey);
        try {
            $request = $server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }

        $user_id = $request->getAttribute('oauth_user_id');

        /** @var UserEntity $user */
        $user = $this->container->get(UserRepository::class)->get([['id', '=', $user_id]]);

        $data = [
            'name' => $user->get_name(),
            'email' => $user->get_mail(),
            'reg_date' => $user->get_reg_date(),
        ];

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
