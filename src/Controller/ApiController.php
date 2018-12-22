<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Controller;

use App\Enum\HttpCodeEnum;
use App\Exception\BadRequestException;
use App\Exception\UndefinedHeaderException;
use App\Exception\UnprocessableEntityException;
use App\Exception\UnsupportedTypeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
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
