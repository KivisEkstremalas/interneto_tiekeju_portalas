<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191109102816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_C7440455A53A8AA ON client (provider_id)');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, manufacturer VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, serial_no VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A298A7A619EB6921 ON computer (client_id)');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('CREATE TABLE provider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649A53A8AA ON user (provider_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE computer');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE user');
    }
}
