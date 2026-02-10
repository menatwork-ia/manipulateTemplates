<?php

declare(strict_types=1);

namespace MenAtWork\ManipulateTemplates\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use MenAtWork\ManipulateTemplates\ManipulateTemplatesBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ManipulateTemplatesBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
