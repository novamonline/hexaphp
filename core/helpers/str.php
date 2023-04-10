<?php  

namespace HexaPHP\Helpers;

class Str
{
    public static function has(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }

    public static function after(string $haystack, string $needle): string
    {
        if (!static::has($haystack, $needle)) {
            return $haystack;
        }

        return substr($haystack, strpos($haystack, $needle) + strlen($needle));
    }

    public static function before(string $haystack, string $needle): string
    {
        return substr($haystack, 0, strpos($haystack, $needle));
    }

    public static function parseAndCall(string $string, $funcDelim ="|", $argDelim = ",")
{
    [$functionName, $arguments] = explode($funcDelim, $string);
    $arguments = explode($argDelim, $arguments);

    // Call the specified function with the specified arguments
    return call_user_func_array([__CLASS__, $functionName], $arguments);
}
}