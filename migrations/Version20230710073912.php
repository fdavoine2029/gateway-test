<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230710073912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_files ADD vendor_column VARCHAR(3) DEFAULT NULL, ADD sku_column VARCHAR(3) DEFAULT NULL, ADD qte_column VARCHAR(3) NOT NULL, ADD delete_sku TINYINT(1) NOT NULL, ADD ligne INT NOT NULL, ADD id_column VARCHAR(3) DEFAULT NULL, ADD sku_tisse_column VARCHAR(3) DEFAULT NULL, ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE107294869C');
        $this->addSql('DROP INDEX IDX_29E1AE107294869C ON sklbl_fx');
        $this->addSql('ALTER TABLE sklbl_fx ADD sklbl_sku_id VARCHAR(255) NOT NULL, ADD sklbl_order_id INT DEFAULT NULL, ADD unique_id VARCHAR(255) DEFAULT NULL, ADD redirect_url VARCHAR(255) DEFAULT NULL, ADD sent_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD received_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD status INT NOT NULL, CHANGE article_id sklbl_file_id INT NOT NULL');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE1016D690DE FOREIGN KEY (sklbl_sku_id) REFERENCES sklbl_sku (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE10E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE105E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('CREATE INDEX IDX_29E1AE1016D690DE ON sklbl_fx (sklbl_sku_id)');
        $this->addSql('CREATE INDEX IDX_29E1AE10E6A07BB4 ON sklbl_fx (sklbl_file_id)');
        $this->addSql('CREATE INDEX IDX_29E1AE105E25CDC ON sklbl_fx (sklbl_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_files DROP vendor_column, DROP sku_column, DROP qte_column, DROP delete_sku, DROP ligne, DROP id_column, DROP sku_tisse_column, DROP status');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE1016D690DE');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE10E6A07BB4');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE105E25CDC');
        $this->addSql('DROP INDEX IDX_29E1AE1016D690DE ON sklbl_fx');
        $this->addSql('DROP INDEX IDX_29E1AE10E6A07BB4 ON sklbl_fx');
        $this->addSql('DROP INDEX IDX_29E1AE105E25CDC ON sklbl_fx');
        $this->addSql('ALTER TABLE sklbl_fx ADD article_id INT NOT NULL, DROP sklbl_sku_id, DROP sklbl_file_id, DROP sklbl_order_id, DROP unique_id, DROP redirect_url, DROP sent_on, DROP received_on, DROP updated_at, DROP status');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE107294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_29E1AE107294869C ON sklbl_fx (article_id)');
    }
}
