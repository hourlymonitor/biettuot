<?php

return [
    'emails' => array_values(array_filter(array_map('trim', explode(',', (string) env('ADMIN_EMAILS', ''))))),
    'moderator_emails' => array_values(array_filter(array_map('trim', explode(',', (string) env('MODERATOR_EMAILS', ''))))),
];
