<?php

namespace Hito\Admin\Builders;

class AdminDeleteResourceBuilder extends AbstractAdminResourceBuilder
{
    protected ?string $submitButton = null;
    protected ?string $cancelButton = null;
    protected ?string $destroyUrl = null;
    protected ?string $cancelUrl = null;
    protected ?string $formTitle = null;
    protected ?string $formDescription = null;
    protected bool $isUsed = false;

    protected function getBaseView(): ?string
    {
        return 'hito-admin::_resource.delete';
    }

    protected function getData(): array
    {
        $submitButton = $this->submitButton ?? __('app.yes');
        $cancelButton = $this->cancelButton ?? __('app.no');

        if ($this->isUsed) {
            $submitButton = null;
            $cancelButton = __('app.show-usage');
            $formTitle = __('forms.prevent_delete.title');

            if (isset($this->entitySingular)) {
                $formDescription = __('forms.prevent_delete.description', ['entity' => $this->entitySingular]);
            }
        } else {
            $formTitle = __('forms.delete_confirmation');

            if (isset($this->entitySingular)) {
                $formDescription = __('forms.delete_prompt', ['entity' => $this->entitySingular]);
            }
        }

        return [
            'submitButton' => $submitButton,
            'cancelButton' => $this->cancelButton ?? $cancelButton,
            'destroyUrl' => $this->destroyUrl,
            'cancelUrl' => $this->cancelUrl,
            'formTitle' => $this->formTitle ?? $formTitle,
            'formDescription' => $this->formDescription ?? $formDescription ?? null,
        ];
    }

    public function submitButton(string $label): self
    {
        $this->submitButton = $label;
        return $this;
    }

    public function cancelButton(string $label): self
    {
        $this->cancelButton = $label;
        return $this;
    }

    public function destroyUrl(string $url): self
    {
        $this->destroyUrl = $url;
        return $this;
    }

    public function cancelUrl(string $url): self
    {
        $this->cancelUrl = $url;
        return $this;
    }

    public function formTitle(string $value): self
    {
        $this->formTitle = $value;
        return $this;
    }

    public function formDescription(string $value): self
    {
        $this->formDescription = $value;
        return $this;
    }

    public function isUsed(bool $value): self
    {
        $this->isUsed = $value;
        return $this;
    }
}
