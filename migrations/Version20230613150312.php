<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613150312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receiv_sup_details (id INT AUTO_INCREMENT NOT NULL, receiv_sup_id INT DEFAULT NULL, qte_recue NUMERIC(10, 2) NOT NULL, comment LONGTEXT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', batch_num VARCHAR(30) NOT NULL, INDEX IDX_DFE6D3319A0C3180 (receiv_sup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receiv_sup_details ADD CONSTRAINT FK_DFE6D3319A0C3180 FOREIGN KEY (receiv_sup_id) REFERENCES receiv_sup (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receiv_sup_details DROP FOREIGN KEY FK_DFE6D3319A0C3180');
        $this->addSql('DROP TABLE receiv_sup_details');
    }
}
