<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Response\Entity;
use App\Security\UsersViewVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @Route("/users")
 */
class UserController extends BaseController
{
    /**
     * @Route("/{id}", methods={"GET"})
     * @param User $user
     * @param Entity $entityResponse

     *
     */
    public function data(User $user, Entity $entityResponse)
    {
        $this->denyAccessUnlessGranted(UsersViewVoter::ATTRIBUTE, $user);

        return $this->handleResponseView($entityResponse->setEntity($user), 200, ['api']);
    }

    /**
     * @Route("/logout")
     */
    public function logout()
    {

    }
}
