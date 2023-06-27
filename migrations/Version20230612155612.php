<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612155612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ART (ART_ID INT AUTO_INCREMENT NOT NULL, DOS VARCHAR(8) NOT NULL, REF VARCHAR(25) NOT NULL, DES VARCHAR(80) NOT NULL, ABCCOD VARCHAR(1) NOT NULL, PRIMARY KEY(ART_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FOU (FOU_ID INT AUTO_INCREMENT NOT NULL, DOS VARCHAR(8) NOT NULL, TIERS VARCHAR(20) NOT NULL, NOM VARCHAR(80) NOT NULL, PAY VARCHAR(3) NOT NULL, TRANSJRNB NUMERIC(3, 0) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(FOU_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, ref VARCHAR(25) NOT NULL, designation VARCHAR(80) NOT NULL, abccod VARCHAR(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseurs (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(80) NOT NULL, country VARCHAR(3) NOT NULL, trspdays NUMERIC(3, 0) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouv (id INT AUTO_INCREMENT NOT NULL, dos VARCHAR(8) NOT NULL, ref VARCHAR(25) NOT NULL, sref1 VARCHAR(8) NOT NULL, sref2 VARCHAR(8) NOT NULL, tiers VARCHAR(20) NOT NULL, enrno NUMERIC(14, 0) NOT NULL, cdno NUMERIC(10, 0) NOT NULL, cddt DATE NOT NULL, des VARCHAR(80) NOT NULL, cdqte NUMERIC(12, 3) NOT NULL, refun VARCHAR(4) NOT NULL, mont NUMERIC(13, 2) NOT NULL, dev VARCHAR(4) NOT NULL, blno NUMERIC(10, 0) NOT NULL, blqte NUMERIC(12, 3) NOT NULL, depo VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_sup (id INT NOT NULL, fournisseurs_id INT NOT NULL, articles_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, order_num NUMERIC(10, 0) NOT NULL, record_num NUMERIC(14, 0) NOT NULL, order_date DATE NOT NULL, designation VARCHAR(80) NOT NULL, buyer VARCHAR(20) NOT NULL, order_qte NUMERIC(15, 3) NOT NULL, unit VARCHAR(4) NOT NULL, amount NUMERIC(13, 2) NOT NULL, currency VARCHAR(4) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, delay DATE NOT NULL, trans NUMERIC(3, 0) NOT NULL, delivery_place VARCHAR(3) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E8E0BCF427ACDDFD (fournisseurs_id), INDEX IDX_E8E0BCF41EBAF6CC (articles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiv_sup (id INT NOT NULL, ordersup_id INT NOT NULL, dossier VARCHAR(8) NOT NULL, delivery_note NUMERIC(10, 0) NOT NULL, batch_num VARCHAR(30) DEFAULT NULL, receiv_qte NUMERIC(12, 3) NOT NULL, commentaire LONGTEXT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', blmod VARCHAR(4) NOT NULL, INDEX IDX_1ED543ADF6DF95AC (ordersup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_sup ADD CONSTRAINT FK_E8E0BCF427ACDDFD FOREIGN KEY (fournisseurs_id) REFERENCES fournisseurs (id)');
        $this->addSql('ALTER TABLE order_sup ADD CONSTRAINT FK_E8E0BCF41EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE receiv_sup ADD CONSTRAINT FK_1ED543ADF6DF95AC FOREIGN KEY (ordersup_id) REFERENCES order_sup (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_sup DROP FOREIGN KEY FK_E8E0BCF427ACDDFD');
        $this->addSql('ALTER TABLE order_sup DROP FOREIGN KEY FK_E8E0BCF41EBAF6CC');
        $this->addSql('ALTER TABLE receiv_sup DROP FOREIGN KEY FK_1ED543ADF6DF95AC');
        $this->addSql('DROP TABLE ART');
        $this->addSql('DROP TABLE FOU');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE fournisseurs');
        $this->addSql('DROP TABLE mouv');
        $this->addSql('DROP TABLE order_sup');
        $this->addSql('DROP TABLE receiv_sup');
    }
}
