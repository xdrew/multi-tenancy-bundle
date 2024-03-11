<?php

declare(strict_types=1);

namespace MultiTenancyBundle\Doctrine\Migration;

use Throwable;
use function strtolower;
use MultiTenancyBundle\Exception\DirectoryMigrationException;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\Configuration\Configuration;

final class DirectoryMigration
{
    /**
     * Get config doctrine migrations
     *
     * @return ConfigurationArray
     */
    public static function getConfiguration(string $entityManagerName, array $fileMigrations): ConfigurationArray
    {
        try {
            $path = $fileMigrations['doctrine_migrations']['migrations_paths'];

            if (strtolower($entityManagerName) === "tenant") {
                $migration = [
                    'DoctrineMigrationsTenant' => $path['DoctrineMigrationsTenant']
                ];
            } else {
                $migration = [
                    'DoctrineMigrations' => $path['DoctrineMigrations']
                ];
            }

            if (!$fileMigrations['doctrine_migrations']['organize_migrations']) {
                $organizeMigration = Configuration::VERSIONS_ORGANIZATION_NONE;
            } else {
                $organizeMigration = constant(Configuration::class .'::VERSIONS_ORGANIZATION_'.$fileMigrations['doctrine_migrations']['organize_migrations']);
            }

            $config = new ConfigurationArray([
                'migrations_paths' => $migration,
                'organize_migrations' => $organizeMigration,
            ]);
        } catch (Throwable) {
            throw new DirectoryMigrationException("The doctrine_migrations.yaml file is invalid.");
        }

        return $config;
    }
}
