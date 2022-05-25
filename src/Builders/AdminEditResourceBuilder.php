<?php

namespace Hito\Admin\Builders;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminEditResourceBuilder extends AbstractAdminResourceBuilder
{
    protected ?string $submitButton = null;
    protected ?string $updateUrl = null;
    protected View|Factory|null $view = null;

    protected function getBaseView(): ?string
    {
        return 'hito-admin::_resource.edit';
    }

    protected function getData(): array
    {
        return [
            'view' => $this->view,
            'submitButton' => $this->submitButton,
            'updateUrl' => $this->updateUrl
        ];
    }

    public function submitButton(string $label): self
    {
        $this->submitButton = $label;
        return $this;
    }

    public function updateUrl(string $url): self
    {
        $this->updateUrl = $url;
        return $this;
    }

    public function view(View|Factory $view): self
    {
        $this->view = $view;
        return $this;
    }
}
