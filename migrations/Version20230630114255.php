<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630114255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bf (id INT AUTO_INCREMENT NOT NULL, dos VARCHAR(8) NOT NULL, pino NUMERIC(10, 0) NOT NULL, ref VARCHAR(25) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(5) DEFAULT NULL, reffo VARCHAR(40) DEFAULT NULL, cdqte NUMERIC(12, 3) NOT NULL, qtelancee NUMERIC(12, 3) NOT NULL, tiers VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cli (id INT NOT NULL, dos VARCHAR(8) NOT NULL, tiers VARCHAR(20) NOT NULL, nom VARCHAR(80) NOT NULL, pay VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(80) NOT NULL, country VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emballages (id INT NOT NULL, article_id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(4) NOT NULL, libelle VARCHAR(40) NOT NULL, unite VARCHAR(4) NOT NULL, qte NUMERIC(12, 3) NOT NULL, ordre INT NOT NULL, INDEX IDX_FA1786EC7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ofs (id INT NOT NULL, article_id INT NOT NULL, code NUMERIC(10, 0) NOT NULL, dossier VARCHAR(8) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, ref_cli VARCHAR(40) DEFAULT NULL, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, INDEX IDX_4661B9A87294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quality_ctrl (id INT AUTO_INCREMENT NOT NULL, order_sup_id INT DEFAULT NULL, ofs_id INT DEFAULT NULL, checked_by_id INT DEFAULT NULL, checked_on DATE NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, INDEX IDX_ADB60D663CF61D18 (order_sup_id), INDEX IDX_ADB60D66BE988D91 (ofs_id), INDEX IDX_ADB60D662199DB86 (checked_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE t033 (id INT AUTO_INCREMENT NOT NULL, dos VARCHAR(8) NOT NULL, ref VARCHAR(25) NOT NULL, embqte NUMERIC(12, 3) NOT NULL, venum VARCHAR(4) NOT NULL, embun VARCHAR(4) NOT NULL, lib VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emballages ADD CONSTRAINT FK_FA1786EC7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE ofs ADD CONSTRAINT FK_4661B9A87294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D663CF61D18 FOREIGN KEY (order_sup_id) REFERENCES order_sup (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D66BE988D91 FOREIGN KEY (ofs_id) REFERENCES ofs (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D662199DB86 FOREIGN KEY (checked_by_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emballages DROP FOREIGN KEY FK_FA1786EC7294869C');
        $this->addSql('ALTER TABLE ofs DROP FOREIGN KEY FK_4661B9A87294869C');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D663CF61D18');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D66BE988D91');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D662199DB86');
        $this->addSql('DROP TABLE bf');
        $this->addSql('DROP TABLE cli');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE emballages');
        $this->addSql('DROP TABLE ofs');
        $this->addSql('DROP TABLE quality_ctrl');
        $this->addSql('DROP TABLE t033');
    }
}
