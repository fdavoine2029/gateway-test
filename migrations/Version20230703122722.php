<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703122722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl DROP FOREIGN KEY FK_53587F7C19EB6921');
        $this->addSql('ALTER TABLE ofs_sklbl DROP FOREIGN KEY FK_53587F7C7294869C');
        $this->addSql('DROP TABLE ofs_sklbl');
        $this->addSql('ALTER TABLE sklbl_of ADD planned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ofs_sklbl (id INT NOT NULL, article_id INT DEFAULT NULL, client_id INT DEFAULT NULL, dossier VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code NUMERIC(10, 0) NOT NULL, ref_cli VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, sref1 VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sref2 VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, qte_n1 NUMERIC(12, 3) NOT NULL, qte_n2 NUMERIC(12, 3) NOT NULL, qte_n3 NUMERIC(12, 3) NOT NULL, qte_n4 NUMERIC(12, 3) NOT NULL, lib_n1 VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lib_n2 VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lib_n3 VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lib_n4 VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, masque VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, fichier_remonte VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nom_fichier1 VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nom_fichier2 VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, niveau_mini_complet VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, options INT DEFAULT NULL, sync INT NOT NULL, INDEX IDX_53587F7C19EB6921 (client_id), INDEX IDX_53587F7C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ofs_sklbl ADD CONSTRAINT FK_53587F7C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ofs_sklbl ADD CONSTRAINT FK_53587F7C7294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sklbl_of DROP planned_at, DROP start_at, DROP end_at');
    }
}
