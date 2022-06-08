<?php

namespace App\Interfaces;

interface ExternalServicesAdapter
{
    public function execute(array $options): array;
}
