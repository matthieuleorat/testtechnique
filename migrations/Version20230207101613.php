<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207101613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE article (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, article_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, posted_by_id INT NOT NULL, content VARCHAR(255) NOT NULL, approval BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C7294869C ON comment (article_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF8697D13 ON comment (comment_id)');
        $this->addSql('CREATE INDEX IDX_9474526C5A6D2235 ON comment (posted_by_id)');
        $this->addSql('CREATE TABLE comment_rate (id INT NOT NULL, comment_id INT NOT NULL, rated_by_id INT NOT NULL, rate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F9DA9DD5F8697D13 ON comment_rate (comment_id)');
        $this->addSql('CREATE INDEX IDX_F9DA9DD5284C0C5B ON comment_rate (rated_by_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, facebook_id VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_rate ADD CONSTRAINT FK_F9DA9DD5F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_rate ADD CONSTRAINT FK_F9DA9DD5284C0C5B FOREIGN KEY (rated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE article_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_rate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C7294869C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF8697D13');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C5A6D2235');
        $this->addSql('ALTER TABLE comment_rate DROP CONSTRAINT FK_F9DA9DD5F8697D13');
        $this->addSql('ALTER TABLE comment_rate DROP CONSTRAINT FK_F9DA9DD5284C0C5B');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_rate');
        $this->addSql('DROP TABLE "user"');
    }
}
