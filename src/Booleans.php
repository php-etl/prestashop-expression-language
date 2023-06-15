<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class Booleans extends ExpressionFunction
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
                $results = [];
                $data = %s;
                foreach ($data as $optionName => $replacements) {
                    if (array_key_exists($optionName, $input['values'])) {
                        $value = $input['values'][$optionName][0]['data'];

                        $results['product_option_value'][]['id'] = $value === true ? $replacements["true"] : $replacements["false"];
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
            if (array_key_exists($optionName, $input['values'])) {
                $value = $input['values'][$optionName][0]['data'];

                $results['product_option_value'][]['id'] = $value === true ? $replacements["true"] : $replacements["false"];
            }
        }

        return $results;
    }
}
