<?php

namespace Hito\Admin\Builders;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminShowResourceBuilder extends AbstractAdminResourceBuilder
{
    private ?string $indexUrl = null;
    private ?string $editUrl = null;
    private ?string $deleteUrl = null;
    protected View|Factory|null $view = null;

    public function __construct()
    {
    }

    protected function getBaseView(): ?string
    {
        return 'hito-admin::_resource.show';
    }

    protected function getData(): array
    {
        return [
            'view' => $this->view,
            'editUrl' => $this->editUrl,
            'indexUrl' => $this->indexUrl,
            'deleteUrl' => $this->deleteUrl
        ];
    }

    public function indexUrl(string $url): self
    {
        $this->indexUrl = $url;
        return $this;
    }

    public function editUrl(string $url): self
    {
        $this->editUrl = $url;
        return $this;
    }

    public function deleteUrl(string $url): self
    {
        $this->deleteUrl = $url;
        return $this;
    }

    public function view(View|Factory $view): self
    {
        $this->view = $view;
        return $this;
    }
}
