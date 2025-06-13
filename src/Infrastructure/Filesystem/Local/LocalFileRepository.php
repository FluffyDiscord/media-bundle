<?php
declare(strict_types=1);

namespace Ranky\MediaBundle\Infrastructure\Filesystem\Local;


use Psr\Log\LoggerInterface;
use Ranky\MediaBundle\Application\CreateMedia\UploadedFileRequest;
use Ranky\MediaBundle\Application\SafeFileName\SafeFileName;
use Ranky\MediaBundle\Domain\Contract\FilePathResolverInterface;
use Ranky\MediaBundle\Domain\Contract\FileRepositoryInterface;
use Ranky\MediaBundle\Domain\Exception\RenameFileException;
use Ranky\MediaBundle\Domain\ValueObject\Dimension;
use Ranky\MediaBundle\Domain\ValueObject\File;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class LocalFileRepository implements FileRepositoryInterface
{

    public function __construct(
        private readonly SafeFileName $safeFileName,
        private readonly FilePathResolverInterface $filePathResolver,
        private readonly LoggerInterface $logger,
        private readonly Filesystem $filesystem,
    ) {
    }

    public function upload(UploadedFileRequest $uploadedFileRequest): File
    {
        $name = $this->safeFileName->__invoke($uploadedFileRequest->name(), $uploadedFileRequest->extension());
        $file = new File(
            $name,
            $name, // TODO: support new directory in path
            $uploadedFileRequest->mime(),
            $uploadedFileRequest->extension(),
            $uploadedFileRequest->size()
        );

        // TODO: support new directory in path
        $this->rename(
            $uploadedFileRequest->path(),
            $this->filePathResolver->resolve($file->name())
        );

        return $file;
    }

    public function delete(string $fileName): void
    {
        $path = $this->filePathResolver->resolve($fileName);
        if (!$this->filesystem->exists($path)) {
            $this->logger->warning('File did not find when trying to delete the file', ['file' => $path]);
            return;
        }

        try {
            $this->filesystem->remove($path);
        } catch (IOException) {
            $this->logger->error('File did not delete', ['file' => $path]);
        }
    }


    public function rename(string $oldPathFileName, string $newPathFileName): void
    {
        if (!$this->filesystem->exists($oldPathFileName)) {
            throw new RenameFileException(
                \sprintf(
                    'File did not find when trying to rename the file %s',
                    $oldPathFileName
                )
            );
        }

        $this->makeDirectory(\pathinfo($newPathFileName, PATHINFO_DIRNAME));

        try {
            $this->filesystem->rename($oldPathFileName, $newPathFileName, true);
        } catch (IOException $IOException) {
            throw new RenameFileException(
                sprintf('Could not move (rename) the file "%s" to "%s".', $oldPathFileName, $newPathFileName),
                previous: $IOException,
            );
        }
    }

    /**
     * @param string $path
     * @return int<0, max>
     */
    public function filesizeFromPath(string $path): int
    {
        if ($this->filesystem->exists($path) && $size = \filesize($path)) {
            return $size;
        }
        $this->logger->warning('The file size could not be obtained. "0" returned', ['file' => $path]);
        return 0;
    }

    public function dimensionsFromPath(string $path, ?string $mime = null): Dimension
    {
        $mime ??= \mime_content_type($path);

        if ($mime && \str_contains($mime, 'image/') && \is_array($dimensions = @getimagesize($path))) {
            return new Dimension($dimensions[0], $dimensions[1]);
        }

        return new Dimension();
    }

    public function makeDirectory(string $path): void
    {
        $this->filesystem->mkdir($path);
    }

}
