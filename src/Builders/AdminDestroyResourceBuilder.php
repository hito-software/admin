<?php

namespace Hito\Admin\Builders;

class AdminDestroyResourceBuilder extends AbstractAdminRedirectResourceBuilder
{
    protected function defaultSuccessMessage(): ?string
    {
        if (empty($this->entitySingular)) {
            return null;
        }

        return __('forms.deleted_successfully', ['entity' => $this->entitySingular]);
    }
}
