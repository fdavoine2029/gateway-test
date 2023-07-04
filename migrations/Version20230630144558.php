<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630144558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl ADD masque VARCHAR(80) DEFAULT NULL, ADD fichier_remonte VARCHAR(80) DEFAULT NULL, ADD nom_fichier1 VARCHAR(80) DEFAULT NULL, ADD nom_fichier2 VARCHAR(80) DEFAULT NULL, ADD niveau_mini_complet VARCHAR(80) DEFAULT NULL, ADD options INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl DROP masque, DROP fichier_remonte, DROP nom_fichier1, DROP nom_fichier2, DROP niveau_mini_complet, DROP options');
    }
}
