<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

final class SaveHomeCard
{
    public function __construct(private KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function save(
        ?string $cardContent,
        string $sessionId,
        string $filename = null
    ): void {
        if (empty($filename)) {
            $filename = 'home.jpeg';
        }
        $dir = sprintf(
            '%s/public/homeCards/%s',
            $this->kernel->getProjectDir(),
            $sessionId
        );
        $path = sprintf(
            '%s/%s',
            $dir,
            $filename
        );
        $filesystem = new Filesystem();
        if ($filesystem->exists($dir)) {
            $this->deleteOldCard($dir, $filesystem);
        }
        $filesystem->dumpFile($path, (string) $cardContent);
    }

    private function deleteOldCard(string $dir, Filesystem $filesystem): void
    {
        $files = (array) \scandir($dir);
        foreach ($files as $key => $file) {
            if (\str_contains((string) $file, 'home')) {
                $filesystem->remove($dir . '/' . $file);
            }
        }
    }
}
