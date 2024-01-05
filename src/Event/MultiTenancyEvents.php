<?php

declare(strict_types=1);

namespace MultiTenancyBundle\Event;

namespace MultiTenancyBundle\Event;

final class MultiTenancyEvents
{
    public const string TENANT_CREATED = 'tenant.created';
    public const string TENANT_REMOVED = 'tenant.removed';
}