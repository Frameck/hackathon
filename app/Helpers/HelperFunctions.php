<?php

namespace App\Helpers;

if (!function_exists('isset_and_not_empty')) {
    /**
     * Controlla isset() e !empty() e ritorna la variabile se passa,
     * altrimenti il valore di default che è stato fornito
     *
     * @param  mixed  $value  variabile da controllare
     * @param  mixed  $default  valore di default che viene restituito se la variabile non passa i controlli
     * @return  mixed  $value || $default
     */
    function isset_and_not_empty(mixed $value, mixed $default = null): mixed
    {
        if (isset($value) && !empty($value)) {
            return $value;
        }

        return $default;
    }
}

if (!function_exists('array_offset_exists_and_is_filled')) {
    /**
     * Controlla isset() e filled() e ritorna true se passa,
     * altrimenti false
     *
     * @param  array  $array  variabile da controllare
     * @param  string  $offset  offset da controllare nell'array
     */
    function array_offset_exists_and_is_filled(array $array, string $offset): bool
    {
        if (!isset($array[$offset]) || !filled($array[$offset])) {
            return false;
        }

        return true;
    }
}

if (!function_exists('in_array_all')) {
    /**
     * Controlla se tutti i valori passati sono nell'array
     *
     * @param  array  $needles  array da controllare
     * @param  array  $haystack  array nel quale effettuare la ricerca
     */
    function in_array_all(array $needles, array $haystack): bool
    {
        return empty(array_diff($needles, $haystack));
    }
}

if (!function_exists('in_array_any')) {
    /**
     * Controlla se almeno un valore passato è nell'array
     *
     * @param  array  $needles  array da controllare
     * @param  array  $haystack  array nel quale effettuare la ricerca
     */
    function in_array_any(array $needles, array $haystack): bool
    {
        return !empty(array_intersect($needles, $haystack));
    }
}

if (!function_exists('array_contains_only_numbers')) {
    /**
     * Controlla se tutti i valori nell'array sono numerici
     *
     * @param  array  $haystack  array nel quale effettuare la ricerca
     */
    function array_contains_only_numbers(array $haystack): bool
    {
        $containsOnlyNumbers = true;

        foreach ($haystack as $value) {
            if (!is_numeric($value)) {
                $containsOnlyNumbers = false;
            }
        }

        return $containsOnlyNumbers;
    }
}
