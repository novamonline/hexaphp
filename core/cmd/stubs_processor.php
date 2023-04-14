<?php 

namespace HexaPHP\Core\Cmd;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use HexaPHP\Helpers\Str;


class StubsProcessor {
    private $placeholders;

    public function setPlaceholders(array $placeholders) {
        $this->placeholders = $placeholders;
    }

    private function camelCase($str) {
        $str = ucwords(str_replace('_', ' ', $str));
        return lcfirst(str_replace(' ', '', $str));
    }

    private function pascalCase($str) {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
    }

    private function snake_case($str) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $str));
    }

    private function titleCase($str) {
        return ucwords(strtolower($str));
    }

    public function replace($content) {
        return preg_replace_callback('/__([\w|]+)__/', function($matches) {
            $placeholder = $matches[1];
            $default = '';
            if (strpos($placeholder, '|') !== false) {
                [$placeholder, $default] = explode('|', $placeholder, 2);
            }
            [$name, $format] = array_pad(explode(':', $placeholder, 2), 2, null);
            $value = $this->placeholders[$name] ?? $default;

            switch ($format) {
                case 'ucfirst':
                    $value = ucfirst($value);
                    break;
                case 'camel':
                    $value = $this->camelCase($value);
                    break;
                case 'pascal':
                    $value = $this->pascalCase($value);
                    break;
                case 'snake':
                    $value = $this->snake_case($value);
                    break;
                case 'title':
                    $value = $this->titleCase($value);
                    break;
            }

            return $value;
        }, $content);
    }
}
