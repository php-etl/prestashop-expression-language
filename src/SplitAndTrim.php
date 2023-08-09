<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

/**
 * Converts to lowercase, splits, and trims
 */
final class SplitAndTrim extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            $this->compile(...)->bindTo($this),
            $this->evaluate(...)->bindTo($this)
        );
    }

    private function compile(string $separator, string $input)
    {
        $pattern = <<<'PHP'
            (function() use ($input) {
                $results = [];
                foreach (explode(%s, mb_convert_case(%s, \MB_CASE_LOWER)) as $element) {
                    $results[] = trim($element);
                }

                return $results;
            })()
            PHP;

        return sprintf($pattern, $separator, $input);
    }

    private function evaluate(array $context, string $separator, string $input)
    {
        $results = [];
        foreach (explode($separator, mb_convert_case($input, \MB_CASE_LOWER)) as $element) {
            $results[] = trim($element);
        }

        return $results;
    }
}
