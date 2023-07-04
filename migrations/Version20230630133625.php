<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630133625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs ADD libelle VARCHAR(40) DEFAULT NULL, ADD order_num NUMERIC(10, 0) DEFAULT NULL, ADD prevstart_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD conditionnement NUMERIC(9, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs DROP libelle, DROP order_num, DROP prevstart_at, DROP start_at, DROP end_at, DROP conditionnement');
    }
}
