<?php

namespace Hito\Admin\Builders;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

abstract class AbstractAdminRedirectResourceBuilder extends AbstractAdminResourceBuilder
{
    private ?string $url = null;
    private ?string $successMessage = null;
    private ?string $failedMessage = null;
    private ?string $route;
    private mixed $routeParameters = [];

    public function __construct(?string $route = null, mixed $routeParameters = [])
    {
        $this->route = $route;
        $this->routeParameters($routeParameters);
    }

    public function route(string $route): self
    {
        $this->route = $route;
        return $this;
    }

    public function routeParameters(mixed $routeParameters): self
    {
        $this->routeParameters = $routeParameters;
        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function successMessage(string $message): self
    {
        $this->successMessage = $message;
        return $this;
    }

    public function failedMessage(string $message): self
    {
        $this->failedMessage = $message;
        return $this;
    }

    public function build(): View|RedirectResponse|Redirector
    {
        if (!empty($this->url)) {
            return redirect($this->url);
        }

        if (empty($this->route)) {
            $redirect = redirect()->back();
        } else {
            $redirect = redirect()->route($this->route, $this->routeParameters);
        }

        if (!empty($this->failedMessage)) {
            return $redirect->with('failed', $this->failedMessage);
        }

        return $redirect->with('success', $this->successMessage ?? $this->defaultSuccessMessage());
    }

    protected function defaultSuccessMessage(): ?string
    {
        return null;
    }
}
