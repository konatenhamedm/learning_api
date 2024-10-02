<?php

namespace  App\Controller\Apis\Config;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface InterfaceMethode
{

    public function index(): Response;
    public function create(): Response;
    public function update(): Response;
    public function delete(): Response;
    public function deleteAll(): Response;
}
