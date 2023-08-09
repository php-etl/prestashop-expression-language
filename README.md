Prestashop Expression Language
===

This package extends the [ExpressionLanguage](https://symfony.com/doc/current/components/expression_language.html) Symfony component to manipulate data coming from Akeneo into valid Prestashop data.

[![Quality (PHPStan lvl 4)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/quality.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/quality.yaml)
[![PHPUnit](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpunit.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpunit.yaml)
[![Infection](https://github.com/php-etl/prestashop-expression-language/actions/workflows/infection.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/infection.yaml)
[![PHPStan level 5](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-5.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-5.yaml)
[![PHPStan level 6](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-6.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-6.yaml)
[![PHPStan level 7](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-7.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-7.yaml)
[![PHPStan level 8](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-8.yaml/badge.svg)](https://github.com/php-etl/prestashop-expression-language/actions/workflows/phpstan-8.yaml)
![PHP](https://img.shields.io/packagist/php-v/php-etl/prestashop-expression-language)

Documentation
---

[See full Documentation](https://php-etl.github.io/documentation)

Installation
---

```
composer require php-etl/prestashop-expression-language
```

Usage
---


You can use these expressions in your configuration files as in the following example :

```yaml
foo: '@=booleans(my_akeneo_attribute_code: {the_akeneo_value_to search for: the_prestashop_id_replacement})'
```

Attribute functions
---

Functions that turn an Akeneo attribute into a Prestashop attribute_option_value.

### booleans

```yaml
# input
{values: {new_collection: [{scope: null, data: true}]}}

#function
field: '[associations][product_option_values][product_option_value]'
expression: 'booleans({ new_collection: {true: 52, false: 53} })'

#output
<associations>
    <product_option_values>
        <product_option_value>
            <id>52</id>
        </product_option_value>
    </product_option_values>
</associations>
```

### lists

```yaml
# input
{values: {categories: ["spring", "summer"]}}

#function
field: '[associations][product_option_values][product_option_value]'
expression: 'lists({ categories: {spring: 74, autumn: 75, summer: 76} })'

#output
<associations>
    <product_option_values>
        <product_option_value>
            <id>74</id>
        </product_option_value>
        <product_option_value>
            <id>76</id>
        </product_option_value>
    </product_option_values>
</associations>
```

### measurements

```yaml
# input
{values: {weigth_net: {scope: null, data: {amount: "341.000"}}}}

#function
field: '[associations][product_option_values][product_option_value]'
expression: 'measurements({ weigth_net: {"340.000": 21, "341.000": 22, "342.000": 23} })'

#output
<associations>
    <product_option_values>
        <product_option_value>
            <id>22</id>
        </product_option_value>
    </product_option_values>
</associations>
```

### scalars

```yaml
# input
{values: {color: {scope: null, data: "navy_blue"}}}

#function
field: '[associations][product_option_values][product_option_value]'
expression: 'scalars({ navy_blue: {salmon: 39, citrus: 40, navy_blue: 41, anthracite: 42} })'

#output
<associations>
    <product_option_values>
        <product_option_value>
            <id>41</id>
        </product_option_value>
    </product_option_values>
</associations>
```

Features function
---

Function that turns an Akeneo attribute into a Prestashop feature.

### booleanAttributeToFeature
```yaml
# input
{values: {color: true, varnish: false}}

# function
- field: '[associations][product_features]'
    expression: >
      booleanAttributeToFeature([
        {
          akeneo_code: 'color',
          prestashop_id: 16,
          values: {
            true: 120,
            false: 121,
          }
        },
        {
          akeneo_code: 'varnish',
          prestashop_id: 20,
          values: {
            true: 63,
            false: 64
          }
        }
      ])
      
# output
<associations>
  <product_features>
    <product_feature>
      <id>16</id>
      <id_feature_value>120</id_feature_value>
    </product_feature>
    <product_feature>
      <id>20</id>
      <id_feature_value>64</id_feature_value>
    </product_feature>
  </product_features>
</associations>
```

### mapFeatures
```yaml
# input
{id_feature_value: '23,24,25', id: 17}

# function
- field: '[associations][product_features]'
  expression: 'mapFeatures(lookup)'

# output
<associations>
  <product_features>
    <product_feature>
      <id>23</id>
      <id_feature_value>17</id_feature_value>
    </product_feature>
    <product_feature>
      <id>24</id>
      <id_feature_value>17</id_feature_value>
    </product_feature>
    <product_feature>
      <id>25</id>
      <id_feature_value>17</id_feature_value>
    </product_feature>
  </product_features>
</associations>
```

### features
```yaml
# input
{values: {norm: {scope: null, data: "ean188"}}}

#function
field: '[associations][product_features]'
expression: >
    features([
        {
            akeneo_code: 'norm',
            prestashop_id: 15,
            values: {
                ean144: 97,
                ean1502_b: 98,
                ean188: 99,
            }
        }
    ])

#output
<associations>
    <product_features>
        <product_feature>
            <id>15</id>
            <id_feature_value>99</id_feature_value>
        </product_feature>
    </product_features>
</associations>
```

### mapIds
```yaml
# input
{1,2,3}

# function
- field: '[categories]'
  expression: 'mapIds(input)'

# output
<categories>
  <id>1</id>
  <id>2</id>
  <id>3</id>
</categories>
```

### splitAndTrim
```yaml
# input
{' Foo, bAr ,BAZ'}

# function
- field: '[words]'
  expression: 'splitAndTrim(",", input)'

# output
<words>
  <0>foo</0>
  <1>bar</1>
  <2>baz</2>
</words>
```