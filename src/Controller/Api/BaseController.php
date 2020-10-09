<?php

namespace App\Controller\Api;

use App\Response\ResponseData;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class BaseController
 */
abstract class BaseController
{
    use ControllerTrait;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param ViewHandlerInterface $viewhandler
     *
     * @required
     */
    public function setViewHandler(ViewHandlerInterface $viewhandler)
    {
        $this->viewhandler = $viewhandler;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @required
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param ResponseData $data
     * @param int          $statusCode
     * @param array|null   $serializeGroups
     *
     * @return Response
     */
    protected function handleResponseView(
        ResponseData $data,
        $statusCode = 200,
        array $serializeGroups = null
    ): Response {
        $view = $this->view($data->getResponseArray(), $statusCode);

        if (null != $serializeGroups) {
            $context = new Context();
            $context->setGroups($serializeGroups);
            $view->setContext($context);
        }

        return $this->handleView($view);
    }

    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied subject.
     *
     * @param $attributes
     * @param null $subject
     * @param string $message
     *
     * @final
     */
    protected function denyAccessUnlessGranted($attributes, $subject = null, string $message = 'Access Denied.')
    {
        if (!$this->isGranted($attributes, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes($attributes);
            $exception->setSubject($subject);

            throw $exception;
        }
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @param $attributes
     * @param null $subject
     *
     * @return bool
     * @final
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attributes, $subject);
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @param string $message
     * @param Exception|null $previous
     *
     * @return AccessDeniedException
     * @final
     */
    protected function createAccessDeniedException(
        string $message = 'Access Denied.',
        Exception $previous = null
    ): AccessDeniedException {
        return new AccessDeniedException($message, $previous);
    }
}
