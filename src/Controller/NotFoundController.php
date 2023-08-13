<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotFoundController extends AbstractController
{
    #[Route('/404', name: '404')]
    public function notFound(): JsonResponse
    {
        $obRouter = $this->container->get('router');
        $arListRoutes = array_map(fn($route) => $route->getPath(), $obRouter->getRouteCollection()->all());

        $arResponseData = [
            'message' => 'Данный маршрут не поддерживается!',
            'routes_list' => array_values($arListRoutes)
        ];
        $obResponse = (new JsonResponse($arResponseData, Response::HTTP_NOT_FOUND));
        return $obResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}