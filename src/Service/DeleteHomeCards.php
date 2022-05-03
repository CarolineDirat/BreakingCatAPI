<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

final class DeleteHomeCards
{
    public function __construct(private KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function __toString()
    {
        return 'DeleteHomeCards';
    }

    public function delete(): int
    {
        $dir = sprintf(
            '%s/public/homeCards',
            $this->kernel->getProjectDir()
        );
        $deletions = 0;
        $dirs = \glob($dir . '/*');
        if ($dirs) {
            $deletions = \count($dirs);
        }
        $filesystem = new Filesystem();
        if ($filesystem->exists($dir)) {
            $filesystem->remove($dir);
        }

        return $deletions;
    }
}
