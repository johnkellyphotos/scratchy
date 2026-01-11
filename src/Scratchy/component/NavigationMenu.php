<?php

namespace Scratchy\component;

use Scratchy\elements\a;
use Scratchy\elements\button;
use Scratchy\elements\i;
use Scratchy\elements\li;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\span;
use Scratchy\elements\ul;
use Scratchy\TagType;

class NavigationMenu extends element
{
    private array $items;

    public function __construct(array $items = [], string $brandHref = '/')
    {
        parent::__construct(TagType::nav, classes: ['navbar', 'navbar-expand-lg', 'navbar-dark', 'bg-dark']);

        $this->items = $items ?: $this->defaultItems();

        $container = new div(classes: ['container-fluid']);
        $this->append($container);

        $brand = new a(classes: ['navbar-brand'], attributes: ['href' => $brandHref]);
        $brand->append(new span(content: APP_NAME));
        $container->append($brand);

        $toggler = new button(
            classes: ['navbar-toggler'],
            attributes: [
                'type' => 'button',
                'data-mdb-collapse-init' => '',
                'data-mdb-target' => '#navbarMain',
                'aria-controls' => 'navbarMain',
                'aria-expanded' => 'false',
                'aria-label' => 'Toggle navigation',
            ]
        );
        $togglerIcon = new i(classes: ['fas', 'fa-bars']);
        $toggler->append($togglerIcon);
        $container->append($toggler);

        $collapse = new div(classes: ['collapse', 'navbar-collapse'], attributes: ['id' => 'navbarMain']);
        $container->append($collapse);

        $ul = new ul(classes: ['navbar-nav', 'me-auto', 'mb-2', 'mb-lg-0']);
        $collapse->append($ul);

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        foreach ($this->items as $item) {
            $ul->append($this->renderItem($item, $path));
        }
    }

    private function defaultItems(): array
    {
        return [
            ['label' => 'Home', 'href' => '/', 'icon' => ['fa-solid', 'fa-house']],
            ['label' => 'Missing page', 'href' => '/this-url-does-not-exist/', 'icon' => ['fa-solid', 'fa-newspaper']],
            [
                'label' => 'Drop down menu',
                'icon' => ['fa-solid', 'fa-user'],
                'children' => [
                    ['label' => 'Profile', 'href' => '/account/profile/'],
                    ['label' => 'Settings', 'href' => '/account/settings/'],
                ],
            ],
            ['label' => 'Error', 'href' => '/error/throw-an-error/', 'icon' => ['fa-solid', 'fa-circle-info']],
        ];
    }

    private function renderItem(array $item, string $path): element
    {
        $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;

        if ($hasChildren) {
            return $this->renderDropdown($item, $path);
        }

        return $this->renderLink($item, $path);
    }

    private function renderLink(array $item, string $path): element
    {
        $href = $item['href'] ?? '#';
        $active = $this->isActive($href, $path);

        $li = new li(classes: ['nav-item']);

        $a = new a(classes: array_values(array_filter([
            'nav-link',
            $active ? 'active' : null,
        ])), attributes: ['href' => $href]);

        if (isset($item['icon']) && is_array($item['icon'])) {
            $icon = new i(classes: array_merge($item['icon'], ['me-2']));
            $a->append($icon);
        }

        $a->append(new span($item['label'] ?? ''));
        $li->append($a);

        return $li;
    }

    private function renderDropdown(array $item, string $path): element
    {
        $label = $item['label'] ?? '';
        $children = $item['children'] ?? [];
        $dropdownId = 'dd_' . substr(md5($label), 0, 10);

        $childActive = false;

        foreach ($children as $child) {
            $href = $child['href'] ?? '#';
            if ($this->isActive($href, $path)) {
                $childActive = true;
                break;
            }
        }

        $li = new li(classes: ['nav-item', 'dropdown']);

        $a = new a(
            classes: array_values(array_filter([
                'nav-link',
                'dropdown-toggle',
                $childActive ? 'active' : null,
            ])),
            attributes: [
                'href' => '#',
                'id' => $dropdownId,
                'role' => 'button',
                'data-mdb-dropdown-init' => '',
                'aria-expanded' => 'false',
            ]
        );

        if (isset($item['icon']) && is_array($item['icon'])) {
            $icon = new i(classes: array_merge($item['icon'], ['me-2']));
            $a->append($icon);
        }

        $a->append(new span($label));
        $li->append($a);

        $ul = new ul(
            classes: ['dropdown-menu', 'dropdown-menu-dark'],
            attributes: ['aria-labelledby' => $dropdownId]
        );
        $li->append($ul);

        foreach ($children as $child) {
            $cHref = $child['href'] ?? '#';
            $cActive = $this->isActive($cHref, $path);

            $cLi = new li();

            $cA = new a(
                classes: array_values(array_filter([
                    'dropdown-item',
                    $cActive ? 'active' : null,
                ])),
                attributes: ['href' => $cHref]
            );

            $cA->append(new span($child['label'] ?? ''));

            // FIX: dropdown <a> must be inside <li> and <li> must be appended to the <ul>
            $cLi->append($cA);
            $ul->append($cLi);
        }

        return $li;
    }

    private function isActive(string $href, string $path): bool
    {
        $hrefPath = parse_url($href, PHP_URL_PATH) ?: $href;

        if ($hrefPath === '/') {
            return $path === '/';
        }

        $hrefPath = rtrim($hrefPath, '/');
        $pathNorm = rtrim($path, '/');

        if ($hrefPath === '') {
            return false;
        }

        return str_starts_with($pathNorm . '/', $hrefPath . '/');
    }
}
