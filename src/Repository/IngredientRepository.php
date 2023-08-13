<?php

namespace App\Repository;

use App\Entity\Ingredient;
use App\Entity\IngredientType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    private ObjectRepository $ingredientTypeRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->ingredientTypeRepository = $registry->getRepository(IngredientType::class);

        parent::__construct($registry, Ingredient::class);
    }

    /**
     * Возвращает массив сопоставлений [код типа => ингредиент]
     *
     * @param array $arCodeLetters
     * @return array Ingredient[string]
     */
    public function getMapCodeIngredient(array $arCodeLetters): array
    {
        $mapCodeIngredient = [];

        $arTypesId = $this->ingredientTypeRepository->getTypesId($arCodeLetters);
        $obIngredients = $this->findBy(['type_id' => $arTypesId]);

        foreach ($obIngredients as $obIngredient) {
            $strCode = $obIngredient->getType()->getCode();
            $mapCodeIngredient[$strCode][] = $obIngredient;
        }
        return $mapCodeIngredient;
    }

}
