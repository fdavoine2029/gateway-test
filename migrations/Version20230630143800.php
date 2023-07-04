<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630143800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl ADD sref1 VARCHAR(8) DEFAULT NULL, ADD sref2 VARCHAR(8) DEFAULT NULL, ADD qte_n1 NUMERIC(12, 3) NOT NULL, ADD qte_n2 NUMERIC(12, 3) NOT NULL, ADD qte_n3 NUMERIC(12, 3) NOT NULL, ADD qte_n4 NUMERIC(12, 3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl DROP sref1, DROP sref2, DROP qte_n1, DROP qte_n2, DROP qte_n3, DROP qte_n4');
    }
}
