<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621071158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receiv_sup_details (id INT AUTO_INCREMENT NOT NULL, order_sup_id INT DEFAULT NULL, qte_recue NUMERIC(10, 2) NOT NULL, comment LONGTEXT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', batch_num VARCHAR(30) NOT NULL, num_bl_fou VARCHAR(10) NOT NULL, INDEX IDX_DFE6D3313CF61D18 (order_sup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receiv_sup_details ADD CONSTRAINT FK_DFE6D3313CF61D18 FOREIGN KEY (order_sup_id) REFERENCES order_sup (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receiv_sup_details DROP FOREIGN KEY FK_DFE6D3313CF61D18');
        $this->addSql('DROP TABLE receiv_sup_details');
    }
}
