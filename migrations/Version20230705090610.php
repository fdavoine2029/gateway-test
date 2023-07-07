<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705090610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_sku (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT NOT NULL, sku VARCHAR(255) NOT NULL, sku_tisse VARCHAR(255) DEFAULT NULL, order_qte INT NOT NULL, produce_qte INT DEFAULT NULL, off_qte INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_94F1BBFD5E25CDC (sklbl_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_sku ADD CONSTRAINT FK_94F1BBFD5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_sku DROP FOREIGN KEY FK_94F1BBFD5E25CDC');
        $this->addSql('DROP TABLE sklbl_sku');
    }
}
