<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class ScalarOptions extends ExpressionFunction
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
            (function() use ($input) {
                $results = $input['association']['product_option_values']['product_option_value'] ?? [];
                $data = %s;
                foreach ($data as $optionName => $replacements) {
                    if (array_key_exists($optionName, $input['values'])) {
                        $value = $input['values'][$optionName][0]['data'];
                        
                        if (!array_key_exists($value, $replacements)) {
                            echo 'Scalar "'.$optionName.'": incoming value "'.$value.'" has no replacement.';
                            continue;
                        }

                        $results[]['id'] = $replacements[$value];
                    }
                }
                
                return $results;
            })()
            PHP;

        return sprintf($pattern, $optionNames);
    }

    private function evaluate(array $context, array $input, array $data)
    {
        $results = [];
        foreach ($data as $optionName => $replacements) {
            if (\array_key_exists($optionName, $input['values'])) {
                $value = $input['values'][$optionName][0]['data'];

                if (!\array_key_exists($value, $replacements)) {
                    echo 'Scalar "'.$optionName.'": incoming value "'.$value.'" has no replacement.';
                    continue;
                }

                $results[]['id'] = $replacements[$value];
            }
        }

        return $results;
    }
}
