<?php

namespace Hito\Admin\Enums;

use Hito\Platform\Traits\TranslatableEnum;

enum ContactType: string
{
    use TranslatableEnum;

    case SKYPE = 'skype';
    case WHATSAPP = 'whatsapp';
    case TELEGRAM = 'telegram';
    case PHONE = 'phone';

    public function translationNamespace(): string
    {
        return 'hito-admin::';
    }
}
