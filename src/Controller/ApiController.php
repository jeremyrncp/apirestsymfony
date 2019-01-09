<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Enum\HttpCodeEnum;
use App\Exception\BadRequestException;
use App\Exception\InvalidArgumentException;
use App\Exception\UndefinedHeaderException;
use App\Exception\UnprocessableEntityException;
use App\Exception\UnsupportedTypeException;
use App\Utils\Api\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    public const ACCEPT_CONTENT_TYPE = "application/json";
    public const ACCEPT_CONTENT_TYPE_FORMAT = "json";
    public const ACCEPT_MIME_TO_FORMAT = [
        "application/json" => "json"
    ];
    public const ENTITY_PER_PAGE = 10;
    public const DEFAULT_OFFSET = 0;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var RouterInterface
     */
    protected $router;


    /**
     * ApiController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, RouterInterface $router)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->router = $router;
    }

    /**
     * @param Pagerfanta $pager
     * @param Pagination $pagination
     * @param int $offset
     * @param string $entityPath
     * @return string
     */
    protected function addLinksInHeader(
        Pagerfanta $pager,
        Pagination $pagination,
        int $offset,
        string $entityPath
    ): string
    {
        $linkList = $pagination->getPagination($offset, $pager->getMaxPerPage(), $pager->getNbResults());

        $links = '';

        foreach ($linkList as $nameLink => $linkOffset) {
            $links .= '<' . $entityPath . $linkOffset . '>; rel="' . $nameLink . '",';
        }

        return $links;
    }

    /**
     * @param Request $request
     * @param Pagination $pagination
     * @param string $fullQualifiedClassName
     * @param string $routeName
     * @return Response
     * @throws InvalidArgumentException
     * @throws UndefinedHeaderException
     * @throws UnsupportedTypeException
     */
    protected function getStandardListRessources(
        Request $request,
        Pagination $pagination,
        string $fullQualifiedClassName,
        string $routeName
    ): Response {

        if (!class_exists($fullQualifiedClassName)) {
            throw new InvalidArgumentException('Full qualified class name isn\'t exist');
        }

        $format = $this->validAcceptTypeAndFetchApiFormat($request);

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('p')->from($fullQualifiedClassName, 'p');

        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager = $this->getPagerWithParamRequest($request, $pager, self::ENTITY_PER_PAGE);

        $response = new Response();
        $links = $this->addLinksInHeader($pager, $pagination, $this->getOffset($request), $this->router->generate($routeName));

        $entities = iterator_to_array($pager->getCurrentPageResults());

        $response->headers->set('Link', $links);
        $response->setContent($this->serializer->serialize($entities, $format));
        $response->setStatusCode(HttpCodeEnum::HTTP_OK);

        return $response;

    }


    /**
     * @param Request $request
     * @param Pagerfanta $pager
     * @param int $limitPerPage
     *
     * @return Pagerfanta
     */
    protected function getPagerWithParamRequest(Request $request, Pagerfanta $pager, int $limitPerPage = self::ENTITY_PER_PAGE)
    {
        $offset = $this->getOffset($request);

        $currentPage = (int) round(ceil($offset + 1) / $limitPerPage);
        $pager->setCurrentPage($currentPage + 1);
        $pager->setMaxPerPage($limitPerPage);

        return $pager;
    }

    /**
     * @param $entity
     * @param ValidatorInterface $validator
     * @throws UnprocessableEntityException
     */
    protected function validateEntity($entity, ValidatorInterface $validator)
    {
        /** @var ConstraintViolationListInterface $validation */
        $validation = $validator->validate($entity);

        if ($validation->count() > 0) {
            throw new UnprocessableEntityException(
                sprintf('Your entity contain errors : %s ', implode(',', $this->getViolationsList(
                    $validation
                ))),
                HttpCodeEnum::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * @param Request $request
     * @return int|mixed
     */
    protected function getOffset(Request $request)
    {
        if ($request->query->has('offset') && is_numeric($request->query->get('offset'))) {
            $offset = $request->query->get('offset');
        } else {
            $offset = self::DEFAULT_OFFSET;
        }
        return $offset;
    }

    /**
     * @param $violationList
     * @return array
     */
    private function getViolationsList($violationList)
    {
        $violationsList = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $violationsList[] = $violation->getMessage();
        }

        return $violationsList;
    }

    /**
     * @param Request $request
     * @return string
     * @throws UndefinedHeaderException
     * @throws UnsupportedTypeException
     */
    protected function validAcceptTypeAndFetchApiFormat(Request $request): string
    {
        if (!$request->headers->has('Accept')) {
            throw new UndefinedHeaderException('Accept header must be defined', HttpCodeEnum::HTTP_EXPECTATION_FAILED);
        }

        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));

        foreach ($this->getParameter('api')['accept'] as $type) {
            if ($acceptHeader->has($type)) {
                return self::ACCEPT_MIME_TO_FORMAT[$type];
            }
        }

        throw new UnsupportedTypeException(
            sprintf('Accept header must be in this list %s ', implode(',', $this->getParameter('api')['accept'])),
            HttpCodeEnum::HTTP_NOT_ACCEPTABLE
        );
    }


    /**
     * @param Request $request
     * @return resource|string
     * @throws BadRequestException
     * @throws UndefinedHeaderException
     * @throws UnsupportedTypeException
     */
    protected function validAndFetchBody(Request $request)
    {
        if (!$request->headers->has('Content-Type')) {
            throw new UndefinedHeaderException('Content-Type header must be defined', HttpCodeEnum::HTTP_EXPECTATION_FAILED);
        }

        if (!$request->headers->get('Content-Type') === self::ACCEPT_CONTENT_TYPE) {
            throw new UnsupportedTypeException('Content-Type must be application/json', HttpCodeEnum::HTTP_NOT_ACCEPTABLE);
        }

        $bodyDecode = json_decode(
            $request->getContent(),
        true
        );

        if (!is_array($bodyDecode)) {
            throw new BadRequestException('Body must be a valid JSON', HttpCodeEnum::HTTP_BAD_REQUEST);
        }

        return $request->getContent();
    }
}
