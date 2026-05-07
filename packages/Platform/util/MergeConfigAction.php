<?php

declare(strict_types=1);

namespace Bites\Platform\Utility;

use Illuminate\Contracts\Config\Repository;

class MergeConfigAction
{
    public function __construct(
        protected Repository $config
    ) {}

    /**
     * Perform a recursive merge of package defaults and user overrides.
     */
    public function execute(string $path, string $key): void
    {
        $packageConfig = require $path;
        $userConfig = $this->config->get($key, []);

        $this->config->set(
            $key,
            array_replace_recursive($packageConfig, $userConfig)
        );
    }
}
