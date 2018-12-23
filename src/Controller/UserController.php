<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Entity\User;
use App\Enum\HttpCodeEnum;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Security\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends ApiController
{
    const NOT_AUTHORIZED = 'You haven\'t authorized to visualise this ressource';

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * UserController constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     */

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->em = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \App\Exception\BadRequestException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnprocessableEntityException
     * @throws \App\Exception\UnsupportedTypeException
     */
    public function createUser(Request $request)
    {
        $accept = $this->validAcceptTypeAndFetchApiFormat($request);

        $body = $this->validAndFetchBody($request);

        /** @var User $entity */
        $user = $this->serializer->deserialize($body, User::class, self::ACCEPT_CONTENT_TYPE_FORMAT);

        $this->validateEntity($user, $this->validator);
        $user->setBusinessCustomer($this->getUser());

        $this->em->persist($user);
        $this->em->flush();

        return new Response(
            $this->serializer->serialize($user, $accept),
            HttpCodeEnum::HTTP_CREATED
        );
    }

    /**
     * @Route("/api/user/{id}", requirements={"id"="\d+"}, methods={"DELETE"}, name="delete_user")
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     *
     * @throws \App\Exception\ForbiddenException
     * @throws \App\Exception\NotFoundException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     **/
    public function deleteUser(Request $request, int $id)
    {
        $this->validAcceptTypeAndFetchApiFormat($request);

        $user = $this->fetchUser($id);

        try {
            $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

            $this->em->remove($user);
            $this->em->flush();

            return new Response('',
                HttpCodeEnum::HTTP_NO_CONTENT
            );
        } catch (AccessDeniedException $e) {
            throw new ForbiddenException(self::NOT_AUTHORIZED, HttpCodeEnum::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param int $id
     * @return User|null|object
     * @throws NotFoundException
     */
    protected function fetchUser(int $id)
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if (null === $user) {
            throw new NotFoundException('User not found', HttpCodeEnum::HTTP_NOT_FOUND);
        }
        return $user;
    }
}
