<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230830140921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_sup (id VARCHAR(255) NOT NULL, fournisseurs_id INT DEFAULT NULL, articles_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, order_num NUMERIC(10, 0) NOT NULL, record_num NUMERIC(14, 0) NOT NULL, order_date DATE NOT NULL, designation VARCHAR(80) NOT NULL, buyer VARCHAR(20) NOT NULL, order_qte NUMERIC(15, 3) NOT NULL, unit VARCHAR(4) NOT NULL, amount NUMERIC(13, 2) NOT NULL, currency VARCHAR(4) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, delay DATE NOT NULL, trans NUMERIC(3, 0) NOT NULL, delivery_place VARCHAR(3) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', to_deliver_qte NUMERIC(15, 3) NOT NULL, delivery_note NUMERIC(10, 0) DEFAULT NULL, batch_num VARCHAR(30) DEFAULT NULL, receiv_qte NUMERIC(12, 3) NOT NULL, comment LONGTEXT DEFAULT NULL, status INT NOT NULL, blmod VARCHAR(4) NOT NULL, delay_trsp DATE NOT NULL, sync INT NOT NULL, etablissement VARCHAR(3) NOT NULL, order_line NUMERIC(4, 0) NOT NULL, bl_line NUMERIC(14, 0) NOT NULL, no_ventilation NUMERIC(14, 0) NOT NULL, INDEX IDX_E8E0BCF427ACDDFD (fournisseurs_id), INDEX IDX_E8E0BCF41EBAF6CC (articles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quality_ctrl (id INT AUTO_INCREMENT NOT NULL, order_sup_id VARCHAR(255) DEFAULT NULL, ofs_id INT DEFAULT NULL, checked_by_id INT DEFAULT NULL, checked_on DATE NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, INDEX IDX_ADB60D663CF61D18 (order_sup_id), INDEX IDX_ADB60D66BE988D91 (ofs_id), INDEX IDX_ADB60D662199DB86 (checked_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiv_sup_details (id INT AUTO_INCREMENT NOT NULL, order_sup_id VARCHAR(255) DEFAULT NULL, qte_recue NUMERIC(10, 2) NOT NULL, comment LONGTEXT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', batch_num VARCHAR(30) DEFAULT NULL, num_bl_fou VARCHAR(10) NOT NULL, INDEX IDX_DFE6D3313CF61D18 (order_sup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_sup ADD CONSTRAINT FK_E8E0BCF427ACDDFD FOREIGN KEY (fournisseurs_id) REFERENCES fournisseurs (id)');
        $this->addSql('ALTER TABLE order_sup ADD CONSTRAINT FK_E8E0BCF41EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D663CF61D18 FOREIGN KEY (order_sup_id) REFERENCES order_sup (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D66BE988D91 FOREIGN KEY (ofs_id) REFERENCES ofs (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D662199DB86 FOREIGN KEY (checked_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE receiv_sup_details ADD CONSTRAINT FK_DFE6D3313CF61D18 FOREIGN KEY (order_sup_id) REFERENCES order_sup (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_sup DROP FOREIGN KEY FK_E8E0BCF427ACDDFD');
        $this->addSql('ALTER TABLE order_sup DROP FOREIGN KEY FK_E8E0BCF41EBAF6CC');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D663CF61D18');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D66BE988D91');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D662199DB86');
        $this->addSql('ALTER TABLE receiv_sup_details DROP FOREIGN KEY FK_DFE6D3313CF61D18');
        $this->addSql('DROP TABLE order_sup');
        $this->addSql('DROP TABLE quality_ctrl');
        $this->addSql('DROP TABLE receiv_sup_details');
    }
}
