<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630142320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ofs_sklbl (id INT AUTO_INCREMENT NOT NULL, dossier VARCHAR(8) NOT NULL, code NUMERIC(10, 0) NOT NULL, ref_cli VARCHAR(40) NOT NULL, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_divalto (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE mrbqval');
        $this->addSql('DROP TABLE t033');
        $this->addSql('ALTER TABLE bf DROP dos, DROP pino, DROP ref, DROP sref1, DROP sref2, DROP reffo, DROP cdqte, DROP qtelancee, DROP tiers');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mrbqval (id INT NOT NULL, dos VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, entiteindex VARCHAR(90) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rubrique VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rbqval VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE t033 (id INT AUTO_INCREMENT NOT NULL, dos VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ref VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, embqte NUMERIC(12, 3) NOT NULL, venum VARCHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, embun VARCHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lib VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE ofs_sklbl');
        $this->addSql('DROP TABLE sklbl_divalto');
        $this->addSql('ALTER TABLE bf ADD dos VARCHAR(8) NOT NULL, ADD pino NUMERIC(10, 0) NOT NULL, ADD ref VARCHAR(25) NOT NULL, ADD sref1 VARCHAR(8) DEFAULT NULL, ADD sref2 VARCHAR(5) DEFAULT NULL, ADD reffo VARCHAR(40) DEFAULT NULL, ADD cdqte NUMERIC(12, 3) NOT NULL, ADD qtelancee NUMERIC(12, 3) NOT NULL, ADD tiers VARCHAR(20) NOT NULL');
    }
}
