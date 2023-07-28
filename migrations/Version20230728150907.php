<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728150907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_sku CHANGE data1 data1 VARCHAR(255) DEFAULT NULL, CHANGE data2 data2 VARCHAR(255) DEFAULT NULL, CHANGE data3 data3 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_sku CHANGE data1 data1 VARBINARY(255) DEFAULT NULL, CHANGE data2 data2 VARBINARY(255) DEFAULT NULL, CHANGE data3 data3 VARBINARY(255) DEFAULT NULL');
    }
}
