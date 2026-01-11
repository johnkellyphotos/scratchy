<?php

namespace core;

readonly class Data
{
    public array $get;
    public array $post;
    public array $files;

    public function __construct()
    {
        $this->get = $this->normalizeArray($_GET);
        $this->post = $this->normalizeArray($_POST);
        $this->files = $_FILES;
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        return !is_null($key) ? ($this->get[$key] ?? $default) : $this->get;
    }

    public function post(?string $key = null, mixed $default = null): mixed
    {
        return !is_null($key) ? ($this->post[$key] ?? $default) : $this->post;
    }

    public function file(?string $key = null, mixed $default = null): mixed
    {
        return !is_null($key) ? ($this->file[$key] ?? $default) : $this->file;
    }

    private function normalizeArray(array $data): array
    {
        $out = [];

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $out[$k] = $this->normalizeArray($v);
                continue;
            }

            $out[$k] = $this->smartCast($v);
        }

        return $out;
    }

    private function smartCast(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $s = trim($value);

        if ($s === '') {
            return $value; // preserve empty or whitespace-only strings as-is
        }

        $lower = strtolower($s);

        if ($lower === 'null') {
            return null;
        }

        if ($lower === 'true') {
            return true;
        }

        if ($lower === 'false') {
            return false;
        }

        // JSON (object/array)
        $first = $s[0] ?? '';
        if ($first === '{' || $first === '[') {
            $decoded = json_decode($s, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        // Integer / float
        if (preg_match('/^[+-]?\d+$/', $s) === 1) {
            // avoid casting huge ints that overflow into float in some builds
            $intVal = (int)$s;
            if ((string)$intVal === ltrim($s, '+')) {
                return $intVal;
            }
        }

        // floating point numbers and scientific notiation
        if (preg_match('/^[+-]?(?:\d+\.\d*|\d*\.\d+)(?:[eE][+-]?\d+)?$/', $s) === 1
            || preg_match('/^[+-]?\d+(?:[eE][+-]?\d+)$/', $s) === 1) {
            return (float)$s;
        }

        return $value;
    }
}
