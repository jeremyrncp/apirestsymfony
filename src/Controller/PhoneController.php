<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Entity\Phone;
use App\Enum\HttpCodeEnum;
use App\Utils\Api\Pagination;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;


class PhoneController extends ApiController
{

    const USERS_PER_PAGE  = 20;
    const DEFAULT_OFFSET = 0;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SerializerInterface
     */
    private $serializer;

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
        $this->validator = $validator;
        $this->em = $entityManager;
        $this->serializer = $serializer;
        $this->router = $router;
    }

    /**
     * @Route("/api/phone", methods={"GET"}, name="view_phones")
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return Response
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     */
    public function viewPhones(Request $request, Pagination $pagination)
    {
        $format = $this->validAcceptTypeAndFetchApiFormat($request);

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('p')->from(Phone::class, 'p');

        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager = $this->getPagerWithParamRequest($request, $pager, self::USERS_PER_PAGE);

        $response = new Response();
        $links = $this->addLinksInHeader($pager, $pagination, $this->getOffset($request), $this->router->generate('view_phones'));

        $users = iterator_to_array($pager->getCurrentPageResults());

        $response->headers->set('Link', $links);
        $response->setContent($this->serializer->serialize($users, $format));
        $response->setStatusCode(HttpCodeEnum::HTTP_OK);

        return $response;
    }

    /**
     * @Route("/api/phone/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="view_phone")
     *
     * @param Request $request
     * @param Phone $phone
     * @return Response
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     */
    public function viewPhone(Request $request, Phone $phone)
    {
        $format = $this->validAcceptTypeAndFetchApiFormat($request);

        return new Response(
            $this->serializer->serialize($phone, $format),
            HttpCodeEnum::HTTP_OK
        );
    }
}
