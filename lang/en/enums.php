<?php

use Hito\Admin\Enums\ContactType;
use Hito\Admin\Enums\Status;

return [
    Status::class => [
        Status::PRIVATE->name => 'Private',
        Status::PUBLIC->name => 'Public'
    ],
    ContactType::class => [
        ContactType::PHONE->name => 'Phone',
        ContactType::SKYPE->name => 'Skype',
        ContactType::TELEGRAM->name => 'Telegram',
        ContactType::WHATSAPP->name => 'WhatsApp',
    ]
];
