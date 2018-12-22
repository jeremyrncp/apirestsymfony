<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Entity\User;
use App\Enum\HttpCodeEnum;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends ApiController
{
    /**
     * @Route("/api/user", methods={"POST"}, name="create_user")
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \App\Exception\BadRequestException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnprocessableEntityException
     * @throws \App\Exception\UnsupportedTypeException
     */
    public function createUser(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $accept = $this->validAcceptTypeAndFetchApiFormat($request);

        $body = $this->validAndFetchBody($request);

        /** @var User $entity */
        $user = $serializer->deserialize($body, User::class, self::ACCEPT_CONTENT_TYPE_FORMAT);

        $this->validateEntity($user, $validator);
        $user->setBusinessCustomer($this->getUser());

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response(
            $serializer->serialize($user, $accept),
            HttpCodeEnum::HTTP_CREATED
        );
    }
}
