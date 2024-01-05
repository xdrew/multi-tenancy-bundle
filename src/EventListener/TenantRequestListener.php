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
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();
        $domain = $request->getHost();

        if ($this->isSubdomain($domain)) {
            // Get tenant
            $site = explode('.', $domain)[0];
            $tenant = $this->hostnameRepository->findOneBy(["fqdn" => $site]);

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

    /**
     * Check if the host is a subdomain
     *
     * @param [string] $url host domain
     * @return boolean
     */
    private function isSubdomain(string $url) : bool
    {
        $exploded = explode('.', $url);
        return (count($exploded) >= 2);
    }
}
