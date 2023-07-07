<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707131314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_fx (id INT AUTO_INCREMENT NOT NULL, sklbl_of_id INT NOT NULL, sklbl_sku_id VARCHAR(255) NOT NULL, sklbl_file_id INT NOT NULL, unique_id VARCHAR(255) DEFAULT NULL, redirect_url VARCHAR(255) DEFAULT NULL, sent_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', received_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, INDEX IDX_29E1AE102038673D (sklbl_of_id), INDEX IDX_29E1AE1016D690DE (sklbl_sku_id), INDEX IDX_29E1AE10E6A07BB4 (sklbl_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE102038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE1016D690DE FOREIGN KEY (sklbl_sku_id) REFERENCES sklbl_sku (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE10E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE102038673D');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE1016D690DE');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE10E6A07BB4');
        $this->addSql('DROP TABLE sklbl_fx');
    }
}
