<?php

namespace Hito\Admin\Builders;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminCreateResourceBuilder extends AbstractAdminResourceBuilder
{
    protected ?string $submitButton = null;
    protected ?string $storeUrl = null;
    protected View|Factory|null $view = null;

    protected function getBaseView(): ?string
    {
        return 'hito-admin::_resource.create';
    }

    protected function getData(): array
    {
        return [
            'view' => $this->view,
            'submitButton' => $this->submitButton,
            'storeUrl' => $this->storeUrl
        ];
    }

    public function submitButton(string $label): self
    {
        $this->submitButton = $label;
        return $this;
    }

    public function storeUrl(string $url): self
    {
        $this->storeUrl = $url;
        return $this;
    }

    public function view(View|Factory $view): self
    {
        $this->view = $view;
        return $this;
    }
}
