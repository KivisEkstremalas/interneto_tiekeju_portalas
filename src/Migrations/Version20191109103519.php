<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191109103519 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_C7440455A53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, provider_id, name, surname FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, surname VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_C7440455A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO client (id, provider_id, name, surname) SELECT id, provider_id, name, surname FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE INDEX IDX_C7440455A53A8AA ON client (provider_id)');
        $this->addSql('DROP INDEX IDX_A298A7A619EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__computer AS SELECT id, client_id, manufacturer, model, serial_no FROM computer');
        $this->addSql('DROP TABLE computer');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, manufacturer VARCHAR(255) NOT NULL COLLATE BINARY, model VARCHAR(255) NOT NULL COLLATE BINARY, serial_no VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_A298A7A619EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO computer (id, client_id, manufacturer, model, serial_no) SELECT id, client_id, manufacturer, model, serial_no FROM __temp__computer');
        $this->addSql('DROP TABLE __temp__computer');
        $this->addSql('CREATE INDEX IDX_A298A7A619EB6921 ON computer (client_id)');
        $this->addSql('DROP INDEX UNIQ_6D28840D19EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, client_id FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, CONSTRAINT FK_6D28840D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO payment (id, client_id) SELECT id, client_id FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('DROP INDEX IDX_8D93D649A53A8AA');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, provider_id, email, password, roles FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, roles VARCHAR(255) NOT NULL, CONSTRAINT FK_8D93D649A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, provider_id, email, password, roles) SELECT id, provider_id, email, password, roles FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE INDEX IDX_8D93D649A53A8AA ON user (provider_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_C7440455A53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, provider_id, name, surname FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO client (id, provider_id, name, surname) SELECT id, provider_id, name, surname FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE INDEX IDX_C7440455A53A8AA ON client (provider_id)');
        $this->addSql('DROP INDEX IDX_A298A7A619EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__computer AS SELECT id, client_id, manufacturer, model, serial_no FROM computer');
        $this->addSql('DROP TABLE computer');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, manufacturer VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, serial_no VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO computer (id, client_id, manufacturer, model, serial_no) SELECT id, client_id, manufacturer, model, serial_no FROM __temp__computer');
        $this->addSql('DROP TABLE __temp__computer');
        $this->addSql('CREATE INDEX IDX_A298A7A619EB6921 ON computer (client_id)');
        $this->addSql('DROP INDEX UNIQ_6D28840D19EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, client_id FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO payment (id, client_id) SELECT id, client_id FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D649A53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, provider_id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO user (id, provider_id, email, roles, password) SELECT id, provider_id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649A53A8AA ON user (provider_id)');
    }
}
