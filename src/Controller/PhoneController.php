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
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * UserController constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, SerializerInterface $serializer, RouterInterface $router)
    {
        $this->validator = $validator;
        parent::__construct($entityManager, $serializer, $router);
    }

    /**
     * @Route("/api/phone", methods={"GET"}, name="view_phones")
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return Response
     * @throws \App\Exception\InvalidArgumentException
     * @throws \App\Exception\UndefinedHeaderException
     * @throws \App\Exception\UnsupportedTypeException
     */
    public function viewPhones(Request $request, Pagination $pagination)
    {
        return $this->getStandardListRessources($request, $pagination, Phone::class, 'view_phones');
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
