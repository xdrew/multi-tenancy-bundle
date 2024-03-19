<?php

namespace MultiTenancyBundle\Doctrine\Database\Dialect\PostgreSql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class PsqlUtils
{
    /**
     * @throws Exception
     */
    public static function createSchema(Connection $connection, string $schema): void
    {
        $connection->executeStatement("CREATE SCHEMA \"{$schema}\"");
    }

    public static function setSchema(Connection $connection, string $schema, bool $addPublic = false): void
    {
        $connection->executeStatement("SET SCHEMA '{$schema}'");
        if ($addPublic === true) {
            $connection->executeStatement("set search_path to '{$schema}', 'public'");
        } else {
            $connection->executeStatement("set search_path to '{$schema}'");
        }
    }
}