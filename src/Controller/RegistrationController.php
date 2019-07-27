<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\User\UserRegistrationException;
use App\Form\Request\RegistrationRequestType;
use App\Model\Request\RegistrationRequest;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @SWG\Post(summary="This endpoint for user registration. On success returns null")
     * @SWG\Tag(name="Login & Registration")
     * @SWG\Parameter(
     *     name="registerRequest",
     *     in="body",
     *     type="object",
     *     description="Registration request object with user credentials",
     *     @Model(type="App\Model\Request\RegistrationRequest")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Registration completed"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Validation error or username already exists"
     * )
     *
     * @Route("/auth/register", name="registration", methods={"POST"})
     */
    public function registerAction(Request $request, UserRepository $repository)
    {
        $registrationRequest = new RegistrationRequest();
        $form = $this->createForm(RegistrationRequestType::class, $registrationRequest);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        try {
            $repository->register($registrationRequest);
        } catch (UserRegistrationException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return null;
    }
}
