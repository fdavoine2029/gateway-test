<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703084219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklb_of (id INT NOT NULL, article_id INT DEFAULT NULL, client_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, code NUMERIC(10, 0) NOT NULL, ref_cli VARCHAR(40) DEFAULT NULL, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, sync INT NOT NULL, INDEX IDX_CE66951D7294869C (article_id), INDEX IDX_CE66951D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_emballage (id INT NOT NULL, article_id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(4) NOT NULL, libelle VARCHAR(40) NOT NULL, unite VARCHAR(4) NOT NULL, qte NUMERIC(12, 3) NOT NULL, ordre INT NOT NULL, INDEX IDX_B03EA41B7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_rubrique (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, entite VARCHAR(90) NOT NULL, rubrique VARCHAR(32) NOT NULL, valeur VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklb_of ADD CONSTRAINT FK_CE66951D7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE sklb_of ADD CONSTRAINT FK_CE66951D19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE sklbl_emballage ADD CONSTRAINT FK_B03EA41B7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('DROP TABLE sklbl_divalto');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_divalto (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sklb_of DROP FOREIGN KEY FK_CE66951D7294869C');
        $this->addSql('ALTER TABLE sklb_of DROP FOREIGN KEY FK_CE66951D19EB6921');
        $this->addSql('ALTER TABLE sklbl_emballage DROP FOREIGN KEY FK_B03EA41B7294869C');
        $this->addSql('DROP TABLE sklb_of');
        $this->addSql('DROP TABLE sklbl');
        $this->addSql('DROP TABLE sklbl_emballage');
        $this->addSql('DROP TABLE sklbl_rubrique');
    }
}
