<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241111224514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, name_product VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price NUMERIC(5, 0) DEFAULT NULL, tva NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_D34A04AD38B53C32 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD38B53C32 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user CHANGE google_id google_id VARCHAR(60) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD38B53C32');
        $this->addSql('ALTER TABLE user CHANGE google_id google_id VARCHAR(60) DEFAULT NULL');
    }
}
