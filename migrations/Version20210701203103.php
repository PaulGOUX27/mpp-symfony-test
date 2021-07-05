<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210701203103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_527EDB25E8A7DCFA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, todo_list_id, message, done FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, todo_list_id INTEGER NOT NULL, message VARCHAR(255) NOT NULL COLLATE BINARY, done BOOLEAN NOT NULL, CONSTRAINT FK_527EDB25E8A7DCFA FOREIGN KEY (todo_list_id) REFERENCES todo_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task (id, todo_list_id, message, done) SELECT id, todo_list_id, message, done FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB25E8A7DCFA ON task (todo_list_id)');
        $this->addSql('DROP INDEX IDX_1B199E07A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__todo_list AS SELECT id, user_id, name FROM todo_list');
        $this->addSql('DROP TABLE todo_list');
        $this->addSql('CREATE TABLE todo_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_1B199E07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO todo_list (id, user_id, name) SELECT id, user_id, name FROM __temp__todo_list');
        $this->addSql('DROP TABLE __temp__todo_list');
        $this->addSql('CREATE INDEX IDX_1B199E07A76ED395 ON todo_list (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_527EDB25E8A7DCFA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, todo_list_id, message, done FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, todo_list_id INTEGER NOT NULL, message VARCHAR(255) NOT NULL, done BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO task (id, todo_list_id, message, done) SELECT id, todo_list_id, message, done FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB25E8A7DCFA ON task (todo_list_id)');
        $this->addSql('DROP INDEX IDX_1B199E07A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__todo_list AS SELECT id, user_id, name FROM todo_list');
        $this->addSql('DROP TABLE todo_list');
        $this->addSql('CREATE TABLE todo_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO todo_list (id, user_id, name) SELECT id, user_id, name FROM __temp__todo_list');
        $this->addSql('DROP TABLE __temp__todo_list');
        $this->addSql('CREATE INDEX IDX_1B199E07A76ED395 ON todo_list (user_id)');
    }
}
