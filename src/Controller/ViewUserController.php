<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

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

    const USERS_PER_PAGE  = 20;
    const DEFAULT_OFFSET = 0;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UserController constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     */

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, SerializerInterface $serializer, RouterInterface $router)
    {
        $this->router = $router;
        parent::__construct($validator, $entityManager, $serializer);
    }


    /**
     * @Route("/api/user", methods={"POST", "GET"}, name="view_delete_user")
     *
     * @param Request $request
     * @param Pagination $pagination
     *
     * @return Response
     *
     * @throws \App\Exception\BadRequestException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnprocessableEntityException
     * @throws \App\Exception\UnsupportedTypeException
     **/
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
     *
     * @return Response
     *
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     */
    private function viewUsers(Request $request, Pagination $pagination)
    {
        $format = $this->validAcceptTypeAndFetchApiFormat($request);

        $pager = $this->getPagerWithParamRequest($request);

        $response = new Response();

        $links = $this->addLinksInHeader($request, $pager, $pagination);

        $users = iterator_to_array($pager->getCurrentPageResults());

        $response->headers->set('Link', $links);
        $response->setContent($this->serializer->serialize($users, $format));
        $response->setStatusCode(HttpCodeEnum::HTTP_OK);

        return $response;
    }

    /**
     * @param Request $request
     * @param Pagerfanta $pager
     * @param Pagination $pagination
     *
     * @return string
     */
    private function addLinksInHeader(Request $request, Pagerfanta $pager, Pagination $pagination): string
    {
        $offset = $this->getOffset($request);

        $linkList = $pagination->getPagination($offset, self::USERS_PER_PAGE, $pager->getNbResults());

        $links = '';

        foreach ($linkList as $nameLink => $linkOffset) {
            $links .= '<' . $this->router->generate('view_delete_user') . $linkOffset . '>; rel="' . $nameLink . '",';
        }

        return $links;
    }


    /**
     * @param Request $request
     * @return Pagerfanta
     */
    private function getPagerWithParamRequest(Request $request)
    {
        $offset = $this->getOffset($request);

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('u')->from('App\Entity\User', 'u');

        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));

        $currentPage = (int) round(ceil($offset + 1) / self::USERS_PER_PAGE);
        $pager->setCurrentPage($currentPage + 1);
        $pager->setMaxPerPage(self::USERS_PER_PAGE);

        return $pager;
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

    /**
     * @param Request $request
     * @return int|mixed
     */
    private function getOffset(Request $request)
    {
        if ($request->query->has('offset') && is_numeric($request->query->get('offset'))) {
            $offset = $request->query->get('offset');
        } else {
            $offset = self::DEFAULT_OFFSET;
        }
        return $offset;
    }
}
