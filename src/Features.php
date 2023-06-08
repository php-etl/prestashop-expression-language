<?php

declare(strict_types=1);

namespace Kiboko\Component\PrestashopExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class Features extends ExpressionFunction
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
                foreach ($data as $feature) {
                    if (array_key_exists($feature['akeneo_code'], $input['values'])) {
                        $value = $input['values'][$feature['akeneo_code']][0]['data'];

                        if ($value === null) {
                            continue;
                        }

                        if (is_iterable($value)) {
                            foreach ($value as $iterableValue) {
                                $results[]['product_feature'] = [
                                    'id' => $feature['prestashop_id'],
                                    'id_feature_value' => $feature['values'][$iterableValue]
                                ];
                            }

                            continue;
                        }

                        $results[]['product_feature'] = [
                            'id' => $feature['prestashop_id'],
                            'id_feature_value' => $feature['values'][$value]
                        ];
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
        foreach ($data as $feature) {
            if (array_key_exists($feature['akeneo_code'], $input['values'])) {
                $value = $input['values'][$feature['akeneo_code']][0]['data'];

                if ($value === null) {
                    continue;
                }

                if (is_iterable($value)) {
                    foreach ($value as $iterableValue) {
                        $results[]['product_feature'] = [
                            'id' => $feature['prestashop_id'],
                            'id_feature_value' => $feature['values'][$iterableValue]
                        ];
                    }

                    continue;
                }

                $results[]['product_feature'] = [
                    'id' => $feature['prestashop_id'],
                    'id_feature_value' => $feature['values'][$value]
                ];
            }
        }

        return $results;
    }
}
