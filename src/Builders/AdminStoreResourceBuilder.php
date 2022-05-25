<?php

namespace Hito\Admin\Builders;

class AdminStoreResourceBuilder extends AbstractAdminRedirectResourceBuilder
{
    protected function defaultSuccessMessage(): ?string
    {
        if (empty($this->entitySingular)) {
            return null;
        }

        return __('forms.created_successfully', ['entity' => $this->entitySingular]);
    }
}
