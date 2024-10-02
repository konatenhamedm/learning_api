<?php

namespace App\Controller\Apis\Config;

use App\Controller\FileTrait;
use App\Service\Menu;
use App\Service\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ApiInterface extends AbstractController
{
    use FileTrait;

    protected const UPLOAD_PATH = 'media_entreprise';
    protected $security;
    protected $validator;
    protected $userInterface;
    protected  $hasher;
    //protected  $utils;
    protected $em;

    protected $client;

    protected $serializer;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client, SerializerInterface $serializer, ValidatorInterface $validator)
    {


        //$this->utils = $utils;
        $this->client = $client;
        $this->em = $em;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }


    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;
    protected $message = "Operation effectuée avec succes";

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }
    protected function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /*  public function serializer(){
        $context = [AbstractNormalizer::GROUPS => 'group1'];
        $json = $this->serializer->serialize($batis, 'json', $context);
    } */

    public function response($data, $headers = [])
    {
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);


        if ($data == null) {
            $arrayData = [
                'data' => '[]',
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()
            ];
            $response = $this->json([
                'data' => $data,
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()

            ], 200);
            $response->headers->set('Access-Control-Allow-Origin', '*');
        } else {
            $arrayData = [
                'data' => $data,
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()
            ];
            $jsonContent = $serializer->serialize($arrayData, 'json', [
                'circular_reference_handler' => function ($object) {
                    return  $object->getId();
                },

            ]);
            // On instancie la réponse
            $response = new Response($jsonContent);
            //$response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }
        // dd($this->json($data));
        // On convertit en json
        // On ajoute l'entête HTTP

        return $response;
        //return new JsonResponse($response, $this->getStatusCode(), $headers);
    }
    public function responseTrue($data, $headers = [])
    {
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);


        if ($data == null) {
            $arrayData = [
                'data' => '[]',
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()
            ];
            $response = $this->json([
                'data' => $data,
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()

            ], 200);
            $response->headers->set('Access-Control-Allow-Origin', '*');
        } else {
            $arrayData = [
                'data' => $data,
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()
            ];
            $jsonContent = $serializer->serialize($arrayData, 'json', [
                'circular_reference_handler' => function ($object) {
                    return  $object->getId();
                },

            ]);
            // On instancie la réponse
            $response = new Response($jsonContent);
            //$response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }
        // dd($this->json($data));
        // On convertit en json
        // On ajoute l'entête HTTP

        return $response;
        //return new JsonResponse($response, $this->getStatusCode(), $headers);
    }



    public function responseAdd($data, $headers = [])
    {
        return $this->json([
            'data' => $data,
            'message' => $this->getMessage(),
            'status' => $this->getStatusCode()

        ], 200);
    }


    public function responseNew($data = [], $group, $headers = [])
    {
        try {
            if ($data) {
                $context = [AbstractNormalizer::GROUPS => $group];
                $json = $this->serializer->serialize($data, 'json', $context);
                $response = new JsonResponse(['code' => 200, 'message' => $this->getMessage(), 'data' => json_decode($json)], 200, $headers);
            } else {
                $response = new JsonResponse(['code' => 200, 'message' => $this->getMessage(), 'data' => []], 200, $headers);
            }
        } catch (\Exception $e) {
            $response = new JsonResponse(['code' => 500, 'message' => $e->getMessage(), 'data' => []], 500, $headers);
        }

        return $response;
    }

    public function responseNew2($data = [], $group, $headers = [])
    {
        try {

            $finalHeaders = empty($headers) ? ['Content-Type' => 'application/json'] : $headers;
            if ($data) {
                ///dd($data);
                // Définir le contexte de sérialisation
                $context = [AbstractNormalizer::GROUPS => $group];
                $json = $this->serializer->serialize($data, 'json', $context);

                // Vérifier si $headers est vide et appliquer les en-têtes en conséquence

                // Créer la réponse JSON avec les en-têtes déterminés

                $response = new JsonResponse([
                    'code' => 200,
                    'message' => $this->getMessage(),
                    'data' => json_decode($json)
                ], 200, $finalHeaders);
            } else {
                // Cas où il n'y a pas de données
                //$finalHeaders = empty($headers) ? ['Content-Type' => 'application/json'] : $headers;

                $response = new JsonResponse([
                    'code' => 200,
                    'message' => $this->getMessage(),
                    'data' => []
                ], 200, $finalHeaders);
            }
        } catch (\Exception $e) {
            // Gestion des exceptions
            //$finalHeaders = empty($headers) ? ['Content-Type' => 'application/json'] : $headers;

            $response = new JsonResponse([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ], 500, $finalHeaders);
        }

        // Retourner la réponse et le code de statut HTTP
        return $response;
    }

    public function errorResponse($DTO): ?JsonResponse
    {
        $errors = $this->validator->validate($DTO);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            $response = [
                'code' => 400,
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ];

            return new JsonResponse($response, 400);
        }

        return null; // Pas d'erreurs, donc pas de réponse d'erreur
    }



    /*  public function responseNew2($data = [], $group, $headers = [])
    {

        try {
            if ($data) {
                $context = [AbstractNormalizer::GROUPS => $group];
                $json = $this->serializer->serialize($data, 'json', $context);
                $response = new JsonResponse([
                    'code' => 200,
                    'message' => $this->getMessage(),
                    'data' => json_decode($json)
                ], 200, array_merge($headers, ['Content-Type' => 'application/json']));
            } else {
                $response = new JsonResponse([
                    'code' => 200,
                    'message' => $this->getMessage(),
                    'data' => []
                ], 200, array_merge($headers, ['Content-Type' => 'application/json']));
            }
        } catch (\Exception $e) {
            $response = new JsonResponse([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ], 500, array_merge($headers, ['Content-Type' => 'application/json']));
        }

        // Retourner la réponse et le code de statut HTTP
        return [
            'response' => $response,
            'status_code' => $response->getStatusCode()
        ];
    } */



    public function sendNotification($data = [])
    {
        $body = [
            "title" => $data['title'],
            "body" => $data['body'],
            "user_id" => $data['userId']
        ];

        try {
            $response = $this->client->request(
                'POST',
                'https://api.freewan.store/auth-center/api/notifications/create',
                [
                    'json' => $body
                ]
            );

            // Afficher le statut de la réponse et le corps de la réponse pour le débogage
            return 'Status Code: ' . $response->getStatusCode();
            // echo 'Response Body: ' . $response->getBody();
        } catch (\Exception $e) {
            // Enregistrer l'erreur pour le débogage
            echo 'Error: ' . $e->getMessage();
        }
    }
}
