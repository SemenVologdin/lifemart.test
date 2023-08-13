<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use App\Utils\IngredientUtil;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", name: 'ingredient_api')]
class IngredientController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route("/ingredients/{strCode}", name: 'ingredients', methods: ['GET'])]
    public function getCombinations(string $strCode, IngredientRepository $ingredientRepository): JsonResponse
    {
        $arCodeLetters = str_split($strCode);
        $mapCodeIngredient = $ingredientRepository->getMapCodeIngredient($arCodeLetters);

        if( empty($mapCodeIngredient) ){
            return $this->json([]);
        }

        $arResult = (new IngredientUtil($mapCodeIngredient, $arCodeLetters))->getCombinations();
        return $this->json($arResult)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}