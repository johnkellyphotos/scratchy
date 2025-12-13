<?php

namespace elements;

use TagType;

class Element
{
    /**
     * @var Element[]
     */
    protected array $childElements = [];
    protected int $depth = 0;

    public function __construct(
        private TagType $tagType,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
        private bool    $usingClosingTag = true,
    )
    {
        $this->classes ??= [];
        $this->attributes ??= [];
    }

    public function append(Element $element): void
    {
        $this->setDepthRecursive($element, $this->depth + 1);
        $this->childElements[] = $element;
    }

    protected function setDepthRecursive(Element $element, int $depth): void
    {
        $element->depth = $depth;
        foreach ($element->childElements as $child) {
            $this->setDepthRecursive($child, $depth + 1);
        }
    }

    public function render(): string
    {
        $indent = false;
        if (defined('_INDENT_')) {
            $indent = _INDENT_ === true;
        }

        $tag = htmlspecialchars($this->tagType->value, ENT_QUOTES, 'UTF-8');
        $html = "<$tag";

        if ($this->id !== null) {
            $id = htmlspecialchars($this->id, ENT_QUOTES, 'UTF-8');
            $html .= " id=\"$id\"";
        }

        if ($this->classes !== []) {
            $safe = array_map(fn($c) => htmlspecialchars($c, ENT_QUOTES, 'UTF-8'), $this->classes);
            $classList = implode(' ', $safe);
            $html .= " class=\"$classList\"";
        }

        if ($this->attributes !== []) {
            foreach ($this->attributes as $name => $value) {
                if (is_int($name)) {
                    $v = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    $html .= " $v";
                } else {
                    if ($name !== "id" && $name !== "class") {
                        $n = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                        $v = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                        $html .= " $n=\"$v\"";
                    }
                }
            }
        }

        if ($this->usingClosingTag) {
            $html .= ">";

            if ($this->content !== null) {
                if ($this instanceof script) {
                    $html .= $this->content;
                } else {
                    $html .= htmlspecialchars($this->content, ENT_QUOTES, 'UTF-8');
                }
            }


            foreach ($this->childElements as $index => $childElement) {
                if ($indent) {
                    if ($index == 0) {
                        $html .= "\n";
                    }
                    $html .= str_repeat("\t", $childElement->depth);
                }
                $html .= $childElement->render();
            }

            if ($indent && count($this->childElements)) {
                $html .= str_repeat("\t", $this->depth);
            }
            $html .= "</$tag>";
        } else {
            $html .= "/>";
        }

        if ($indent) {
            $html .= "\n";
        }
        return $html;
    }

    public function output(): void
    {
        echo $this->render();
    }

    public function createId(): string
    {
        static $id = 0;
        $id++;
        return 'e_' . $id;
    }

    public function class(mixed $classes = null): ?string
    {
        if (is_string($classes)) {
            $this->classes[] = $classes;
        } elseif (is_array($classes)) {
            $this->classes = array_unique(array_merge($this->classes, $classes));
        } elseif (is_null($classes)) {
            return implode(' ', $this->classes);
        }

        return null;
    }

    protected function getInputDefault(string $name): ?string
    {
        return (string)($_REQUEST[$name] ?? null);
    }

    protected function makeUniqueName(): string
    {
        return $this->createId();
    }
}