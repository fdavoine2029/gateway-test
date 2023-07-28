<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728122222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_fx (id INT AUTO_INCREMENT NOT NULL, sklbl_of_id INT NOT NULL, sklbl_sku_id INT NOT NULL, sklbl_file_id INT NOT NULL, sklbl_order_id INT DEFAULT NULL, unique_id VARCHAR(255) DEFAULT NULL, redirect_url VARCHAR(255) DEFAULT NULL, sent_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', received_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, INDEX IDX_29E1AE102038673D (sklbl_of_id), INDEX IDX_29E1AE1016D690DE (sklbl_sku_id), INDEX IDX_29E1AE10E6A07BB4 (sklbl_file_id), INDEX IDX_29E1AE105E25CDC (sklbl_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_fx2 (id VARBINARY(255) NOT NULL, sklbl_fx_id INT DEFAULT NULL, sklbl_file_id INT DEFAULT NULL, sklbl_of_id INT DEFAULT NULL, sklbl_custfile_id INT DEFAULT NULL, of_num INT NOT NULL, sku VARCHAR(255) NOT NULL, sku_tisse VARCHAR(255) DEFAULT NULL, unique_id VARCHAR(255) DEFAULT NULL, redirect_url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deals_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gen_scalabel_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, sklbl_filename VARCHAR(255) NOT NULL, INDEX IDX_74B4FC79DEE25E0 (sklbl_fx_id), INDEX IDX_74B4FC7E6A07BB4 (sklbl_file_id), INDEX IDX_74B4FC72038673D (sklbl_of_id), INDEX IDX_74B4FC71B40BBA6 (sklbl_custfile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_sku (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT NOT NULL, sklbl_file_id INT NOT NULL, order_qte INT NOT NULL, produce_qte INT DEFAULT NULL, off_qte INT DEFAULT NULL, status INT NOT NULL, opt_data1 VARCHAR(255) DEFAULT NULL, opt_data2 VARCHAR(255) DEFAULT NULL, opt_data3 VARCHAR(255) DEFAULT NULL, opt_data4 VARCHAR(255) DEFAULT NULL, opt_data5 VARCHAR(255) DEFAULT NULL, opt_data6 VARCHAR(255) DEFAULT NULL, opt_data7 VARCHAR(255) DEFAULT NULL, opt_data8 VARCHAR(255) DEFAULT NULL, opt_data9 VARCHAR(255) DEFAULT NULL, opt_data10 VARCHAR(255) DEFAULT NULL, INDEX IDX_94F1BBFD5E25CDC (sklbl_order_id), INDEX IDX_94F1BBFDE6A07BB4 (sklbl_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE102038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE1016D690DE FOREIGN KEY (sklbl_sku_id) REFERENCES sklbl_sku (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE10E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE105E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC79DEE25E0 FOREIGN KEY (sklbl_fx_id) REFERENCES sklbl_fx (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC7E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC72038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC71B40BBA6 FOREIGN KEY (sklbl_custfile_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_sku ADD CONSTRAINT FK_94F1BBFD5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_sku ADD CONSTRAINT FK_94F1BBFDE6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE102038673D');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE1016D690DE');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE10E6A07BB4');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE105E25CDC');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC79DEE25E0');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC7E6A07BB4');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC72038673D');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC71B40BBA6');
        $this->addSql('ALTER TABLE sklbl_sku DROP FOREIGN KEY FK_94F1BBFD5E25CDC');
        $this->addSql('ALTER TABLE sklbl_sku DROP FOREIGN KEY FK_94F1BBFDE6A07BB4');
        $this->addSql('DROP TABLE sklbl_fx');
        $this->addSql('DROP TABLE sklbl_fx2');
        $this->addSql('DROP TABLE sklbl_sku');
    }
}
