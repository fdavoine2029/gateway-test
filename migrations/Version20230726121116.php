<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726121116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_files DROP vendor_column, DROP sku_column, DROP qte_column, DROP delete_sku, DROP ligne, DROP id_column, DROP sku_tisse_column');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_files ADD vendor_column VARCHAR(3) DEFAULT NULL, ADD sku_column VARCHAR(3) DEFAULT NULL, ADD qte_column VARCHAR(3) NOT NULL, ADD delete_sku TINYINT(1) NOT NULL, ADD ligne INT NOT NULL, ADD id_column VARCHAR(3) DEFAULT NULL, ADD sku_tisse_column VARCHAR(3) DEFAULT NULL');
    }
}
