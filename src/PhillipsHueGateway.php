<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;

interface PhillipsHueGateway
{
    public function getConfig(): PhillipsHueConfig;

    public function getLight(int $id): Light;

    public function getAllLights(): Collection;
}
