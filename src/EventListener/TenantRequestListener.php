<?php

declare(strict_types=1);

namespace MultiTenancyBundle\EventListener;

use Throwable;
use MultiTenancyBundle\Doctrine\DBAL\TenantConnectionInterface;
use MultiTenancyBundle\Exception\TenantNotFound;
use MultiTenancyBundle\Exception\TenantConnectionException;
use MultiTenancyBundle\Repository\HostnameRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class TenantRequestListener
{
    public function __construct(private TenantConnectionInterface $tenantConnection, private HostnameRepository $hostnameRepository)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();
        $subdomain = $request->headers->get('x-subdomain');

        if ($subdomain) {
            // Get tenant
            $tenant = $this->hostnameRepository->findOneBy(["fqdn" => $subdomain]);

            if (!$tenant) {
                throw new TenantNotFound();
            }

            try {
                // Set tenant connection
                $tenantDb = $tenant->getTenant()->getUuid();
                $this->tenantConnection->getDriverConnection();
                $this->tenantConnection->tenantConnect($tenantDb);
            } catch (Throwable) {
                throw new TenantConnectionException("Error connecting to tenant");
            }
        }
    }
}
