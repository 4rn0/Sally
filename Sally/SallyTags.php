<?php

namespace Statamic\Addons\Sally;

use Statamic\Exceptions\ParsingException;
use Statamic\Extend\Tags;

class SallyTags extends Tags
{

    public function __call($name, $args)
    {

    	$faker = $this->getFakerFactory();
    	$methods = $this->getFakerMethods($faker);

    	if (array_key_exists($name, $methods))
    	{

            $method = $methods[$name];
            $parameters = $this->getParameters($method);
            $output = call_user_func_array([$faker, $name], $parameters);

    		return $this->transformOutput($output);

    	}

    	throw new ParsingException('Sally can\'t fake a \'' . $name . '\' ...');

    }

    private function getFakerFactory()
    {
    	
    	$locale = $this->getParam('locale') ?: $this->getAppLocale(); 
    	$faker = \Faker\Factory::create($locale);

    	return $faker;

    }

    private function getAppLocale()
    {

    	$locale  = app()->getLocale();
    	$locale .= '_' . strtoupper($locale);

    	return $locale;

    }

    private function getFakerMethods($faker)
    {

    	$providers = $faker->getProviders();
    	$return = [];

    	foreach ($providers as $provider)
    	{

    		$return = array_merge($return, $this->getFakerProviderMethods($provider));

    	}

    	return $return;

    }

    private function getFakerProviderMethods($provider)
    {

    	$class = new \ReflectionClass($provider);
    	$methods = $class->getMethods();
		$return = [];

		foreach ($methods as $method)
		{

			if (!$method->isConstructor())
			{

				$return[$method->getName()] = $this->getFakerProviderMethodParameters($method);

			}

		}

		return $return;

    }

    private function getFakerProviderMethodParameters($method)
    {

    	$params = $method->getParameters();
    	$return = [];

		foreach ($params as $param)
		{

			$return[$param->getName()] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;

		}

		return $return;

    }

    private function getParameters($method)
    {

        $parameters = [];

        foreach ($method as $key => $value)
        {

            $param = array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $value;

            if (is_integer($value))
            {

                $param = (int) $param;

            }

            if (is_float($value))
            {

                $param = (float) $param;

            }

            if (is_array($value))
            {

                $param = substr($param, 1, -1);
                $param = str_getcsv($param, ',', '\'');

            }

            $parameters[$key] = $param;

        }

        return $parameters;

    }

    private function transformOutput($output)
    {

        if (is_a($output, 'DateTime'))
        {

            $output = \Carbon::instance($output);

        }

        return $output;

    }
    
}