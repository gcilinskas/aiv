<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\User\CreateType;
use App\Response\Entity;
use App\Service\UserService;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthController
 * @Route("/auth")
 */
class AuthController extends BaseController
{
    /**
     * @Route("/register")
     *
     * @param Request $request
     * @param Entity $response
     * @param FormFactoryInterface $formFactory
     * @param UserService $userService
     *
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        Entity $response,
        FormFactoryInterface $formFactory,
        UserService $userService
    ) {
        $user = new User();
        $form = $formFactory->create(CreateType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $userService->create($user);
            return $this->handleResponseView($response->setEntity($user), 200, ['api_user']);
        }

        return $this->handleView($this->view($form));
    }

    /**
     * @Route("/login_check")
     */
    public function login()
    {

    }

    /**
     * @Route("/logout")
     */
    public function logout()
    {

    }
}
