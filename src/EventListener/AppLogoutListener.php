<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AppLogoutListener
{
    public function __construct() {}

    public function onSymfonyComponentSecurityHttpEventLogoutEvent(): void
    {
    }
}
