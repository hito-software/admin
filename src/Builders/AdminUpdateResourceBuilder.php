<?php

namespace Hito\Admin\Builders;

class AdminUpdateResourceBuilder extends AbstractAdminRedirectResourceBuilder
{
    protected function defaultSuccessMessage(): ?string
    {
        if (empty($this->entitySingular)) {
            return null;
        }

        return __('forms.updated_successfully', ['entity' => $this->entitySingular]);
    }
}
