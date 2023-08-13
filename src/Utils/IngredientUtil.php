<?php

namespace App\Utils;

use Doctrine\Common\Collections\ArrayCollection;

class IngredientUtil
{
    private array $arCodeLetters;
    private array $mapCodeIngredient;

    private array $result = [];
    private array $copyResult = [];

    public function __construct($mapCodeIngredient, $arCodeLetters)
    {
        $this->arCodeLetters = $arCodeLetters;
        $this->mapCodeIngredient = $mapCodeIngredient;
    }

    /**
     * Основной метод получения данных.
     * Получает все возможные комбинации ингредиентов.
     *
     * @return array
     */
    public function getCombinations(): array
    {
        foreach ($this->arCodeLetters as $intIndex => $strCodeLetter) {
            $this->copyResult = $this->result;
            $this->result = [];

            foreach ($this->mapCodeIngredient[$strCodeLetter] as $obIngredient) {
                $this->result[] = $this->getIngredients($obIngredient, $intIndex);
            }

            $this->result = array_merge(...$this->result);
        }

        return $this->processedData();
    }

    /**
     * @param $obIngredient
     * @param $intIndex
     * @return array|\array[][]
     */
    private function getIngredients($obIngredient, $intIndex): array
    {
        $intIngredientId = $obIngredient->getId();
        // region Если первая итерация
        if ($intIndex === 0) {
            return [
                [
                    'products' => [
                        $intIngredientId => $obIngredient
                    ],
                ]
            ];
        }
        // endregion

        // region Добавление в уже существующий данные
        $arCopyResult = $this->copyResult;
        foreach ($arCopyResult as &$arRow) {
            if (array_key_exists($intIngredientId, $arRow['products'])) {
                continue;
            }

            $arRow['products'][$intIngredientId] = $obIngredient;
        }
        unset($arRow);
        // endregion

        return $arCopyResult;
    }

    /**
     * Метод возвращает обработанные данные
     * Приводит в необходимый вид, и находит сумму товаров
     *
     * @return array
     */
    private function processedData(): array
    {
        return (new ArrayCollection($this->result))
            ->filter(function ($arElem) {
                // Убираем лишние значения
                return count($arElem['products']) === count($this->arCodeLetters);
            })
            ->map(function ($arElem) {
                // Суммируем стоимость
                $arElem['price'] = array_reduce($arElem['products'], function ($intAcc, $obProduct) {
                    return $intAcc + $obProduct->getPrice();
                }, 0);

                return $arElem;
            })
            ->map(function ($arElem) {
                // Приводим продукты в необходимый вид
                $arElem['products'] = array_map(function ($obIngredient) {
                    return $obIngredient->toJson();
                }, $arElem['products']);

                return $arElem;
            })
            ->map(function ($arElem) {
                // Сбрасываем ключи
                $arElem['products'] = array_values($arElem['products']);
                return $arElem;
            })
            ->getValues();
    }
}