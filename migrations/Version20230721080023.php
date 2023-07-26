<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230721080023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 ADD sklbl_of_id INT DEFAULT NULL, CHANGE sku sku VARCHAR(255) NOT NULL, CHANGE sku_tisse sku_tisse VARCHAR(255) DEFAULT NULL, CHANGE unique_id unique_id VARCHAR(255) DEFAULT NULL, CHANGE redirect_url redirect_url VARCHAR(255) NOT NULL, CHANGE sklbl_filename sklbl_filename VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC72038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('CREATE INDEX IDX_74B4FC72038673D ON sklbl_fx2 (sklbl_of_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC72038673D');
        $this->addSql('DROP INDEX IDX_74B4FC72038673D ON sklbl_fx2');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP sklbl_of_id, CHANGE sku sku VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE sku_tisse sku_tisse VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE unique_id unique_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE redirect_url redirect_url VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE sklbl_filename sklbl_filename VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
