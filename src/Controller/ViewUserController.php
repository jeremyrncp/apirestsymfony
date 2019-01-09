<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Entity\User;
use App\Enum\HttpCodeEnum;
use App\Exception\ForbiddenException;
use App\Security\UserVoter;
use App\Utils\Api\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ViewUserController extends UserController
{
    /**
     * ViewUserController constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param RouterInterface $router
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, SerializerInterface $serializer, RouterInterface $router)
    {
        parent::__construct($validator, $entityManager, $serializer, $router);
    }

    /**
     * @Route("/api/user", methods={"POST", "GET"}, name="view_or_delete_user")
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return Response
     * @throws \App\Exception\BadRequestException
     * @throws \App\Exception\InvalidArgumentException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnprocessableEntityException
     * @throws \App\Exception\UnsupportedTypeException
     */
    public function userViewOrDelete(Request $request, Pagination $pagination)
    {
        if ($request->isMethod("POST")) {
            return $this->createUser($request);
        }
        else if ($request->isMethod("GET")) {
            return $this->viewUsers($request, $pagination);
        }
    }

    /**
     * @param Request $request
     * @param Pagination $pagination
     * @return Response
     * @throws \App\Exception\InvalidArgumentException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     */
    private function viewUsers(Request $request, Pagination $pagination)
    {
        return $this->getStandardListRessources($request, $pagination, User::class, 'view_or_delete_user');
    }


    /**
     * @Route("/api/user/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="view_user")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     *
     * @throws \App\Exception\ForbiddenException
     * @throws \App\Exception\NotFoundException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     **/
    public function viewUser(Request $request, int $id)
    {
        $format = $this->validAcceptTypeAndFetchApiFormat($request);

        $user = $this->fetchUser($id);

        try {
            $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);

            return new Response(
                $this->serializer->serialize($user, $format),
                HttpCodeEnum::HTTP_OK
            );
        } catch (AccessDeniedException $e) {
            throw new ForbiddenException(self::NOT_AUTHORIZED, HttpCodeEnum::HTTP_FORBIDDEN);
        }
    }
}
