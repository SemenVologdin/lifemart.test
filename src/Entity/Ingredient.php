<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: 'ingredient')]
class Ingredient implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::TEXT)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $type_id;

    #[ORM\OneToOne(targetEntity: IngredientType::class)]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private IngredientType $type;

    #[ORM\Column(type: Types::STRING)]
    private string $title;

    #[ORM\Column(type: Types::DECIMAL)]
    private string $price;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type_id' => $this->getTypeId(),
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'price' => $this->getPrice(),
        ];
    }

    public function toJson(): array
    {
        return [
            'type' => $this->getType()->getTitle(),
            'value' => $this->getTitle()
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Ingredient
     */
    public function setId(int $id): Ingredient
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type_id;
    }

    /**
     * @param int $type_id
     * @return Ingredient
     */
    public function setTypeId(int $type_id): Ingredient
    {
        $this->type_id = $type_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Ingredient
     */
    public function setTitle(string $title): Ingredient
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return Ingredient
     */
    public function setPrice(string $price): Ingredient
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return IngredientType
     */
    public function getType(): IngredientType
    {
        return $this->type;
    }

    /**
     * @param IngredientType $type
     * @return Ingredient
     */
    public function setType(IngredientType $type): Ingredient
    {
        $this->type = $type;
        return $this;
    }
}