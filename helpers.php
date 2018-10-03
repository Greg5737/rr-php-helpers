<?php

if (!function_exists('is_assoc')) {
    /**
     * @param array $arr
     * @return bool
     */
    function is_assoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (!function_exists('camel_to_underscore')) {
    function camel_to_underscore($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}

if (!function_exists('advanced_merge')) {
    /**
     * @param array $a array to be merged to
     * @param array $b array to be merged from. You can specify additional arrays via third argument,
     *                 fourth argument etc.
     *
     * @return array the merged array (the original arrays are not changed.)
     */
    function advanced_merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_int($k)) {
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = advanced_merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }
}

if (!function_exists('str_hyphenate')) {
    /**
     * @param string $string
     * @param int $length
     * @param string $delimiter
     * @return string
     */
    function str_hyphenate(string $string, int $length = 4, string $delimiter = '-')
    {
        return implode($delimiter, str_split($string, $length));
    }
}

if (!function_exists('to_decimal')) {
    /**
     * @param mixed $value
     * @param int $decimals
     * @return float
     */
    function to_decimal($value, int $decimals = 2)
    {
        return (float)number_format($value, $decimals);
    }
}

if (!function_exists('sha256_rand')) {
    /**
     * @return string
     * @throws Exception
     */
    function sha256_rand()
    {
        return hash('sha256', random_int(PHP_INT_MIN, PHP_INT_MAX));
    }
}

if (!function_exists('random_string')) {
    /**
     * @param int $length
     * @return bool|string
     * @throws Exception
     */
    function random_string(int $length)
    {
        return substr(sha256_rand(), 0, $length);
    }
}

if (!function_exists('dd2')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed $args
     * @return void
     */
    function dd2(...$args)
    {
        foreach ($args as $x) {
            print_r($x);
            print_r(PHP_EOL);
        }

        die(1);
    }
}

if (!function_exists('array_unset_value')) {
    /**
     * @param array $array
     * @param $value
     */
    function array_unset_value(array &$array, $value)
    {
        if (($key = array_search($value, $array)) !== false) {
            unset($array[$key]);
        }
    }
}

if (!function_exists('random_boolean')) {
    /**
     * @return bool
     */
    function random_boolean()
    {
        return rand(0, 1) == 1;
    }
}

if (!function_exists('json_file_to_array')) {
    /**
     * @param string $pathToFile
     * @return mixed
     * @throws Exception
     */
    function json_file_to_array(string $pathToFile)
    {
        if (!file_exists($pathToFile)) {
            throw new \Exception(sprintf('Not found source file [%s]', $pathToFile), 500);
        }
        $json = file_get_contents($pathToFile);
        $array = json_decode($json, true);

        if (0 < $errorCode = json_last_error()) {
            throw new \Exception(sprintf('Wrong format file [%s]', $pathToFile), 500);
        }

        return $array;
    }
}

if (!function_exists('array_key_exists_recursive')) {
    /**
     * @param mixed $key
     * @param array|ArrayObject $arr <p>
     * @return bool
     */
    function array_key_exists_recursive($key, $arr)
    {
        if (array_key_exists($key, $arr)) {
            return true;
        }
        foreach ($arr as $currentKey => $value) {
            if (is_array($value)) {
                return array_key_exists_recursive($key, $value);
            }
        }
        return false;
    }
}

if (!function_exists('url_remove_sub_domain')) {
    /**
     * @param string $string
     * @param bool $keepWww
     * @param bool $keepScheme
     * @return string
     * @throws Exception
     */
    function url_remove_sub_domain(string $string, $keepWww = false, $keepScheme = false)
    {
        $parse = parse_url($string);

        if (empty($parse['scheme'])) {
            throw new \Exception('String must contain protocol', 500);
        }

        $www = str_contains($string, '//www.') && $keepWww ? 'www.' : '';
        $scheme = $keepScheme ? $parse['scheme'] . '://' : '';

        $host_names = explode('.', $parse['host']);
        return $scheme . $www . trim($host_names[count($host_names) - 2] . '.' . $host_names[count($host_names) - 1], '.');
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('url_extract_sub_domain')) {
    /**
     * @param string $string
     * @return mixed
     * @throws Exception
     */
    function url_extract_sub_domain(string $string)
    {
        $parse = parse_url($string);

        if (empty($parse['scheme'])) {
            throw new \Exception('String must contain protocol', 500);
        }

        $host_names = explode('.', $parse['host']);

        return array_shift($host_names);
    }
}

if (!function_exists('object_to_array')) {
    /**
     * Function to Convert stdClass Objects to Multidimensional Arrays
     *
     * @param object|array $value
     * @return array
     */
    function object_to_array($value)
    {
        return json_decode(json_encode($value), true);
    }
}

if (!function_exists('custom_array_diff')) {

    function custom_array_diff(array $array1, array $array2)
    {
        return array_udiff_assoc($array1, $array2, function($a, $b){

            if (($a instanceof \DateTime && $b instanceof \DateTime) ||
                ($a instanceof \DateTime && is_string($b)) ||
                ($b instanceof \DateTime && is_string($a))
            ) {
                return $a == $b ? 0 : -1;
            }

            if (is_object($a) || is_object($b) || is_array($a) || is_array($b)) {
                return -1;
            }

            return $a == $b ? 0 : -1;
        });
    }
}