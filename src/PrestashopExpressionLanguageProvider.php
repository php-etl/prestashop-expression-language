<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class PrestashopExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new Booleans('booleans'),
            new Lists('lists'),
            new Measurements('measurements'),
            new Scalars('scalars'),
            new Features('features'),
        ];
    }
}
