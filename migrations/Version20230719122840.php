<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719122840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_fx2 (id INT AUTO_INCREMENT NOT NULL, sklbl_fx_id INT DEFAULT NULL, sklbl_file_id INT DEFAULT NULL, of_num INT NOT NULL, sku VARCHAR(255) NOT NULL, sku_tisse VARCHAR(255) DEFAULT NULL, unique_id VARCHAR(255) DEFAULT NULL, redirect_url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deals_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gen_scalabel_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, sklbl_filename VARCHAR(255) NOT NULL, INDEX IDX_74B4FC79DEE25E0 (sklbl_fx_id), INDEX IDX_74B4FC7E6A07BB4 (sklbl_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC79DEE25E0 FOREIGN KEY (sklbl_fx_id) REFERENCES sklbl_fx (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC7E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC79DEE25E0');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC7E6A07BB4');
        $this->addSql('DROP TABLE sklbl_fx2');
    }
}
