<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209192056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense_note (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, company_id INT NOT NULL, note_date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, submission_date DATE NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_7B1AB476A76ED395 (user_id), INDEX IDX_7B1AB476979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense_note ADD CONSTRAINT FK_7B1AB476A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expense_note ADD CONSTRAINT FK_7B1AB476979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_note DROP FOREIGN KEY FK_7B1AB476A76ED395');
        $this->addSql('ALTER TABLE expense_note DROP FOREIGN KEY FK_7B1AB476979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE expense_note');
        $this->addSql('DROP TABLE user');
    }
}
