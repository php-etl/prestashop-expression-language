<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

/**
 * Converts to lowercase, trims, truncates and splits based on a separator
 */
final class SplitAndTruncate extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            $this->compile(...)->bindTo($this),
            $this->evaluate(...)->bindTo($this)
        );
    }

    private function compile(string $separator, string $input, string $limit)
    {
        $pattern = <<<'PHP'
            (function() use ($input) {
                $results = [];
                foreach (explode(%s, mb_convert_case(%s, \MB_CASE_LOWER)) as $element) {
                    $element = trim(mb_convert_case($element, \MB_CASE_LOWER));
                    
                    if (mb_strlen($element) > %d) {
                        $results[] = mb_substr($element, 0, %d - 1)  . '…';
                    } else {
                        $results[] = $element;
                    }
                }

                return $results;
            })()
            PHP;

        return sprintf($pattern, $separator, $input, $limit, $limit);
    }

    private function evaluate(array $context, string $separator, string $input, int $limit)
    {
        $results = [];
        foreach (explode($separator, mb_convert_case($input, \MB_CASE_LOWER)) as $element) {
            $element = trim(mb_convert_case($element, \MB_CASE_LOWER));

            if (mb_strlen($element) > $limit) {
                $results[] = mb_substr($element, 0, $limit - 1).'…';
            } else {
                $results[] = $element;
            }
        }

        return $results;
    }
}
