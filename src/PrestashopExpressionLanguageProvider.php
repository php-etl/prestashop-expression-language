<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class PrestashopExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new Booleans('booleans'),
            new BooleanAttributeToFeature('booleanAttributeToFeature'),
            new Lists('lists'),
            new MapFeatures('mapFeatures'),
            new MapIds('mapIds'),
            new Measurements('measurements'),
            new ScalarOptions('scalars'),
            new SplitAndTruncate('splitAndTruncate'),
        ];
    }
}
