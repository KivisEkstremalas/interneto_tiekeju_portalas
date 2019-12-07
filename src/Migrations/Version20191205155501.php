<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191205155501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE credit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, used BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1CC16EFE19EB6921 ON credit (client_id)');
        $this->addSql('DROP INDEX IDX_C7440455A53A8AA');
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, provider_id, user_id, name, surname FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, surname VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_C7440455A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO client (id, provider_id, user_id, name, surname) SELECT id, provider_id, user_id, name, surname FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE INDEX IDX_C7440455A53A8AA ON client (provider_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('DROP INDEX IDX_A298A7A619EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__computer AS SELECT id, client_id, manufacturer, model, serial_no FROM computer');
        $this->addSql('DROP TABLE computer');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, manufacturer VARCHAR(255) NOT NULL COLLATE BINARY, model VARCHAR(255) NOT NULL COLLATE BINARY, serial_no VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_A298A7A619EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO computer (id, client_id, manufacturer, model, serial_no) SELECT id, client_id, manufacturer, model, serial_no FROM __temp__computer');
        $this->addSql('DROP TABLE __temp__computer');
        $this->addSql('CREATE INDEX IDX_A298A7A619EB6921 ON computer (client_id)');
        $this->addSql('DROP INDEX IDX_6D28840D19EB6921');
        $this->addSql('DROP INDEX IDX_6D28840DA53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, client_id, provider_id, paid, amount, date FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, provider_id INTEGER DEFAULT NULL, paid BOOLEAN NOT NULL, amount DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, CONSTRAINT FK_6D28840DA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6D28840D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO payment (id, client_id, provider_id, paid, amount, date) SELECT id, client_id, provider_id, paid, amount, date FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE INDEX IDX_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('CREATE INDEX IDX_6D28840DA53A8AA ON payment (provider_id)');
        $this->addSql('DROP INDEX UNIQ_92C4739CA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__provider AS SELECT id, name FROM provider');
        $this->addSql('DROP TABLE provider');
        $this->addSql('CREATE TABLE provider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO provider (id, name) SELECT id, name FROM __temp__provider');
        $this->addSql('DROP TABLE __temp__provider');
        $this->addSql('DROP INDEX IDX_6117D13BA53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__purchase AS SELECT id, provider_id, purchase, date, amount FROM purchase');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('CREATE TABLE purchase (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, purchase VARCHAR(255) NOT NULL COLLATE BINARY, date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_6117D13BA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO purchase (id, provider_id, purchase, date, amount) SELECT id, provider_id, purchase, date, amount FROM __temp__purchase');
        $this->addSql('DROP TABLE __temp__purchase');
        $this->addSql('CREATE INDEX IDX_6117D13BA53A8AA ON purchase (provider_id)');
        $this->addSql('DROP INDEX IDX_8D93D649A53A8AA');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, provider_id, email, password, role FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, role VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_8D93D649A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, provider_id, email, password, role) SELECT id, provider_id, email, password, role FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A53A8AA ON user (provider_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP INDEX IDX_C7440455A53A8AA');
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, provider_id, user_id, name, surname FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO client (id, provider_id, user_id, name, surname) SELECT id, provider_id, user_id, name, surname FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE INDEX IDX_C7440455A53A8AA ON client (provider_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('DROP INDEX IDX_A298A7A619EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__computer AS SELECT id, client_id, manufacturer, model, serial_no FROM computer');
        $this->addSql('DROP TABLE computer');
        $this->addSql('CREATE TABLE computer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, manufacturer VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, serial_no VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO computer (id, client_id, manufacturer, model, serial_no) SELECT id, client_id, manufacturer, model, serial_no FROM __temp__computer');
        $this->addSql('DROP TABLE __temp__computer');
        $this->addSql('CREATE INDEX IDX_A298A7A619EB6921 ON computer (client_id)');
        $this->addSql('DROP INDEX IDX_6D28840DA53A8AA');
        $this->addSql('DROP INDEX IDX_6D28840D19EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, provider_id, client_id, paid, amount, date FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, client_id INTEGER DEFAULT NULL, paid BOOLEAN NOT NULL, amount DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO payment (id, provider_id, client_id, paid, amount, date) SELECT id, provider_id, client_id, paid, amount, date FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE INDEX IDX_6D28840DA53A8AA ON payment (provider_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D19EB6921 ON payment (client_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__provider AS SELECT id, name FROM provider');
        $this->addSql('DROP TABLE provider');
        $this->addSql('CREATE TABLE provider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, user_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO provider (id, name) SELECT id, name FROM __temp__provider');
        $this->addSql('DROP TABLE __temp__provider');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92C4739CA76ED395 ON provider (user_id)');
        $this->addSql('DROP INDEX IDX_6117D13BA53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__purchase AS SELECT id, provider_id, purchase, date, amount FROM purchase');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('CREATE TABLE purchase (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, purchase VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO purchase (id, provider_id, purchase, date, amount) SELECT id, provider_id, purchase, date, amount FROM __temp__purchase');
        $this->addSql('DROP TABLE __temp__purchase');
        $this->addSql('CREATE INDEX IDX_6117D13BA53A8AA ON purchase (provider_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX UNIQ_8D93D649A53A8AA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, provider_id, email, role, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, provider_id, email, role, password) SELECT id, provider_id, email, role, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649A53A8AA ON user (provider_id)');
    }
}
