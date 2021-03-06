<?php

/**
 * @noinspection PhpUndefinedMethodInspection
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Masfernandez\Tests\MusicLabel\Catalog\Application\Album\Delete;

use Masfernandez\MusicLabel\Catalog\Application\Album\Delete\AlbumDeleter;
use Masfernandez\MusicLabel\Catalog\Domain\Model\Album\AlbumNotFound;
use Masfernandez\MusicLabel\Catalog\Domain\Model\Album\AlbumRepository;
use Masfernandez\Tests\MusicLabel\Catalog\Domain\Model\Album\AlbumMother;
use Mockery;
use PHPUnit\Framework\TestCase;

class AlbumDeleterTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldDeleteAnAlbum(): void
    {
        $command = DeleteAlbumCommandMother::create();
        $album   = AlbumMother::create($command->id());

        $albumRepository = Mockery::mock(AlbumRepository::class);
        $albumRepository->allows()->getById($command->id())->andReturns($album);
        $albumRepository->allows()->delete($album);

        (new AlbumDeleter($albumRepository))->execute($command);
    }

    /**
     * @test
     */
    public function itShouldResponseNotFoundException(): void
    {
        $command         = DeleteAlbumCommandMother::create();
        $albumRepository = Mockery::mock(AlbumRepository::class);
        $albumRepository->allows()->getById($command->id())->andReturns(null);

        // test application service
        $albumDeleter = new AlbumDeleter($albumRepository);
        $this->expectException(AlbumNotFound::class);
        $albumDeleter->execute($command);
    }
}
