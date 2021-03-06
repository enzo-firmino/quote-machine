<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310102427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, auteur_id INT NOT NULL, content VARCHAR(255) NOT NULL, meta VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, INDEX IDX_6B71CBF412469DE2 (category_id), INDEX IDX_6B71CBF460BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, date_inscription DATE NOT NULL, experience INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF412469DE2');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF460BB6FE6');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE quote');
        $this->addSql('DROP TABLE user');
    }
}
