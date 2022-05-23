<?php

namespace Hito\Admin\Enums;

use Hito\Platform\Traits\TranslatableEnum;

enum Status: string
{
    use TranslatableEnum;

    case PUBLIC = 'public';
    case PRIVATE = 'private';

    public function translationNamespace(): string
    {
        return 'hito-admin::';
    }
}
