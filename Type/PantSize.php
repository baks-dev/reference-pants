<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *   limitations under the License.
 *
 */

declare(strict_types=1);

namespace BaksDev\Reference\Pants\Type;

use BaksDev\Reference\Pants\Type\Sizes\Collection\PantSizeInterface;
use InvalidArgumentException;

final class PantSize
{

    public const TYPE = 'pants_size_type';

    private PantSizeInterface $size;


    public function __construct(PantSizeInterface|self|string $size)
    {
        if(is_string($size) && class_exists($size))
        {
            $instance = new $size();

            if($instance instanceof PantSizeInterface)
            {
                $this->size = $instance;
                return;
            }
        }

        if($size instanceof PantSizeInterface)
        {
            $this->size = $size;
            return;
        }

        if($size instanceof self)
        {
            $this->size = $size->getPantSize();
            return;
        }

        /** @var PantSizeInterface $declare */
        foreach(self::getDeclared() as $declare)
        {
            if($declare::equals($size))
            {
                $this->size = new $declare;
                return;
            }
        }

        throw new InvalidArgumentException(sprintf('Not found PantSize %s', $size));

    }


    public function __toString(): string
    {
        return $this->size->getvalue();
    }


    /** Возвращает значение ColorsInterface */
    public function getPantSize(): PantSizeInterface
    {
        return $this->size;
    }


    /** Возвращает значение ColorsInterface */
    public function getPantSizeValue(): string
    {
        return $this->size->getValue();
    }


    public static function cases(): array
    {
        $case = [];

        foreach(self::getDeclared() as $key => $size)
        {
            /** @var PantSizeInterface $size */
            $sizes = new $size;
            $case[$sizes::sort().$key] = new self($sizes);
        }

        ksort($case);

        return $case;
    }


    public static function getDeclared(): array
    {
        return array_filter(
            get_declared_classes(),
            static function($className)
                {
                    return in_array(PantSizeInterface::class, class_implements($className), true);
                },
        );
    }


    public function equals(mixed $status): bool
    {
        $status = new self($status);

        return $this->getPantSizeValue() === $status->getPantSizeValue();
    }
}