<?php

declare(strict_types=1);

namespace Masfernandez\Tests\MusicLabel\Auth\Domain\Model\JsonWebToken;

use Masfernandez\MusicLabel\Auth\Domain\Model\JsonWebToken\JwTokenValue;
use Masfernandez\Tests\Shared\Infrastructure\PhpUnit\FakerMother;

class JwTokenValueMother
{
    public static function create(?string $value = null): JwTokenValue
    {
        return new JwTokenValue(
            $value ?? FakerMother::random()->lexify(
                '??????????.?????????????????????.?????????????????????????????????'
            )
        );
    }
}
