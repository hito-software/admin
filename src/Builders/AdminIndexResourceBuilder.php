<?php

namespace Hito\Admin\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminIndexResourceBuilder extends AbstractAdminResourceBuilder
{
    private ?string $createUrl = null;
    private array $callbacks = [];

    public function __construct(private LengthAwarePaginator $items, callable $itemCallback)
    {
        $this->callbacks['item'] = $itemCallback;
    }

    protected function getBaseView(): ?string
    {
        return 'hito-admin::_resource.index';
    }

    protected function getData(): array
    {
        return [
            'items' => $this->items,
            'processedItems' => $this->processItems($this->items, $this->callbacks['item']),
            'createUrl' => $this->createUrl
        ];
    }

    protected function processItems(LengthAwarePaginator $items, callable $itemCallback): Collection
    {
        $processedItems = collect(); // @phpstan-ignore-line

        foreach ($items as $item) {
            $processedItems->push([
                'view' => $itemCallback($item),
                'showUrl' => $this->urlCallback('show', $item),
                'editUrl' => $this->urlCallback('edit', $item),
                'deleteUrl' => $this->urlCallback('delete', $item),
            ]);
        }

        return $processedItems;
    }

    public function createUrl(string $url): self
    {
        $this->createUrl = $url;
        return $this;
    }

    public function showUrl(callable $callback): self
    {
        $this->callbacks['show'] = $callback;
        return $this;
    }

    public function editUrl(callable $callback): self
    {
        $this->callbacks['edit'] = $callback;
        return $this;
    }

    public function deleteUrl(callable $callback): self
    {
        $this->callbacks['delete'] = $callback;
        return $this;
    }

    private function urlCallback(string $type, mixed $item): ?string
    {
        if (empty($this->callbacks[$type])) {
            return null;
        }

        return $this->callbacks[$type]($item);
    }
}
