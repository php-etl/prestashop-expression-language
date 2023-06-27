<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class MapIds extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            $this->compile(...)->bindTo($this),
            $this->evaluate(...)->bindTo($this)
        );
    }

    private function compile(string $optionNames)
    {
        $pattern = <<<'PHP'
            (function() use ($input, $lookup) {
                $results = [];
                $data = %s;
                foreach ($data as $category) {
                    $results[]['id'] = $category;
                }

                return $results;
            })()
            PHP;

        return sprintf($pattern, $optionNames);
    }

    private function evaluate(array $context, array $data)
    {
        $results = [];
        foreach ($data as $category) {
            $results[]['id'] = $category;
        }

        return $results;
    }
}
