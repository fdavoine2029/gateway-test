<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703090253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_of (id INT NOT NULL, article_id INT DEFAULT NULL, client_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, code NUMERIC(10, 0) NOT NULL, ref_cli VARCHAR(40) DEFAULT NULL, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, sync INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_22C283A7294869C (article_id), INDEX IDX_22C283A19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE emballages DROP FOREIGN KEY FK_FA1786EC7294869C');
        $this->addSql('ALTER TABLE sklb_of DROP FOREIGN KEY FK_CE66951D7294869C');
        $this->addSql('ALTER TABLE sklb_of DROP FOREIGN KEY FK_CE66951D19EB6921');
        $this->addSql('DROP TABLE emballages');
        $this->addSql('DROP TABLE rubriques');
        $this->addSql('DROP TABLE sklb_of');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emballages (id INT NOT NULL, article_id INT NOT NULL, dossier VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code VARCHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, libelle VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, unite VARCHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, qte NUMERIC(12, 3) NOT NULL, ordre INT NOT NULL, INDEX IDX_FA1786EC7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rubriques (id INT NOT NULL, dossier VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, entite VARCHAR(90) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rubrique VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, valeur VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sklb_of (id INT NOT NULL, article_id INT DEFAULT NULL, client_id INT DEFAULT NULL, dossier VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code NUMERIC(10, 0) NOT NULL, ref_cli VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, sref1 VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sref2 VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sync INT NOT NULL, INDEX IDX_CE66951D7294869C (article_id), INDEX IDX_CE66951D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE emballages ADD CONSTRAINT FK_FA1786EC7294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sklb_of ADD CONSTRAINT FK_CE66951D7294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sklb_of ADD CONSTRAINT FK_CE66951D19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A7294869C');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A19EB6921');
        $this->addSql('DROP TABLE sklbl_of');
    }
}
