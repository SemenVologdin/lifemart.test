<?php

namespace App\Repository;

use App\Entity\IngredientType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientType>
 *
 * @method IngredientType|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientType|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientType[]    findAll()
 * @method IngredientType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientType::class);
    }

    /**
     * Возвращает массив ID типов, коды которых переданы в аргумент
     *
     * @param array $arCodeLetters
     * @return array
     */
    public function getTypesId(array $arCodeLetters): array
    {
        return (new ArrayCollection($this->findBy(['code' => $arCodeLetters])))
            ->map(fn($obType) => $obType->getId())
            ->getValues();
    }
}
