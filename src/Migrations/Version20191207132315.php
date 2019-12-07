<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191207132315 extends AbstractMigration
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
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, provider_id, user_id, name, surname, contract_name FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, surname VARCHAR(255) NOT NULL COLLATE BINARY, contract_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, contract_signed BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO client (id, provider_id, user_id, name, surname, contract_name) SELECT id, provider_id, user_id, name, surname, contract_name FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('DROP INDEX IDX_A298A7A619EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__computer AS SELECT id, client_id, manufacturer, model, serial_no FROM computer');
        $this->addSql('DROP TABLE computer');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, manufacturer VARCHAR(255) NOT NULL COLLATE BINARY, model VARCHAR(255) NOT NULL COLLATE BINARY, serial_no VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO computer (id, client_id, manufacturer, model, serial_no) SELECT id, client_id, manufacturer, model, serial_no FROM __temp__computer');
        $this->addSql('DROP TABLE __temp__computer');
        $this->addSql('DROP INDEX IDX_1CC16EFE19EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__credit AS SELECT id, client_id, amount FROM credit');
        $this->addSql('DROP TABLE credit');
        $this->addSql('CREATE TABLE credit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO credit (id, client_id, amount) SELECT id, client_id, amount FROM __temp__credit');
        $this->addSql('DROP TABLE __temp__credit');
        $this->addSql('DROP INDEX IDX_6D28840D19EB6921');
        $this->addSql('DROP INDEX IDX_6D28840DA53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, client_id, provider_id, paid, amount, date FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, provider_id INTEGER DEFAULT NULL, paid BOOLEAN NOT NULL, amount DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO payment (id, client_id, provider_id, paid, amount, date) SELECT id, client_id, provider_id, paid, amount, date FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('DROP INDEX IDX_6117D13BA53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__purchase AS SELECT id, provider_id, purchase, date, amount FROM purchase');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('CREATE TABLE purchase (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, purchase VARCHAR(255) NOT NULL COLLATE BINARY, date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO purchase (id, provider_id, purchase, date, amount) SELECT id, provider_id, purchase, date, amount FROM __temp__purchase');
        $this->addSql('DROP TABLE __temp__purchase');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, name, surname, contract_name, provider_id, user_id FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, contract_name VARCHAR(255) DEFAULT NULL, provider_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO client (id, name, surname, contract_name, provider_id, user_id) SELECT id, name, surname, contract_name, provider_id, user_id FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('CREATE INDEX IDX_C7440455A53A8AA ON client (provider_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__computer AS SELECT id, manufacturer, model, serial_no, client_id FROM computer');
        $this->addSql('DROP TABLE computer');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, manufacturer VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, serial_no VARCHAR(255) NOT NULL, client_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO computer (id, manufacturer, model, serial_no, client_id) SELECT id, manufacturer, model, serial_no, client_id FROM __temp__computer');
        $this->addSql('DROP TABLE __temp__computer');
        $this->addSql('CREATE INDEX IDX_A298A7A619EB6921 ON computer (client_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__credit AS SELECT id, amount, client_id FROM credit');
        $this->addSql('DROP TABLE credit');
        $this->addSql('CREATE TABLE credit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, client_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO credit (id, amount, client_id) SELECT id, amount, client_id FROM __temp__credit');
        $this->addSql('DROP TABLE __temp__credit');
        $this->addSql('CREATE INDEX IDX_1CC16EFE19EB6921 ON credit (client_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, paid, amount, date, provider_id, client_id FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, paid BOOLEAN NOT NULL, amount DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, provider_id INTEGER DEFAULT NULL, client_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO payment (id, paid, amount, date, provider_id, client_id) SELECT id, paid, amount, date, provider_id, client_id FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE INDEX IDX_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('CREATE INDEX IDX_6D28840DA53A8AA ON payment (provider_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__purchase AS SELECT id, purchase, date, amount, provider_id FROM purchase');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('CREATE TABLE purchase (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, purchase VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, provider_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO purchase (id, purchase, date, amount, provider_id) SELECT id, purchase, date, amount, provider_id FROM __temp__purchase');
        $this->addSql('DROP TABLE __temp__purchase');
        $this->addSql('CREATE INDEX IDX_6117D13BA53A8AA ON purchase (provider_id)');
    }
}
