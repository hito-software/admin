<?php

namespace Hito\Admin\Builders;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

abstract class AbstractAdminResourceBuilder
{
    protected string $entitySingular;
    protected string $entityPlural;
    protected ?string $title = null;
    protected ?string $pageTitle = null;

    protected function getData(): array {
        return [];
    }

    protected function getBaseView(): ?string {
        return null;
    }

    public function entity(string $singular, string $plural): self
    {
        $this->entitySingular = $singular;
        $this->entityPlural = $plural;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function pageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    public function build(): View|RedirectResponse|Redirector
    {
        return view(
            $this->getBaseView(),
            [
                'entity' => [
                    'singular' => $this->entitySingular ?? null,
                    'plural' => $this->entityPlural ?? null
                ],
                'title' => $this->title,
                'pageTitle' => $this->pageTitle ?? $this->title,
                ...$this->getData()
            ]
        );
    }
}
