<?php

namespace App\Event\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class JWTDecodedListener
 */
class JWTDecodedListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $frontendBaseURL;

    /**
     * JWTDecodedListener constructor.
     *
     * @param RouterInterface $route
     * @param string          $frontendBaseURL
     */
    public function __construct(RouterInterface $router, string $frontendBaseURL)
    {
        $this->router = $router;
        $this->frontendBaseURL = $frontendBaseURL;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['data'] = [
            'id' => $user->getId(),
            'role' => $user->getRole(),
            'redirect' => $this->generateUrlByRole($user->getRole(), ['bearer' => $data['token']]),
        ];

        $event->setData($data);
    }

    /**
     * @param JWTExpiredEvent $event
     */
    public function onJwtExpiredResponse(JWTExpiredEvent $event): void
    {
        $pathInfo = $this->router->getContext()->getPathInfo();
        if (substr($pathInfo, 0, 6) === '/admin') {
            $event->setResponse(new RedirectResponse($this->frontendBaseURL));
        }
    }

    /**
     * generating full routing path
     *
     * @param string $role
     * @param array  $parameters
     *
     * @return string
     */
    private function generateUrlByRole(string $role, array $parameters = []): string
    {
        // regular frontend url
        return $this->frontendBaseURL;
    }

}
