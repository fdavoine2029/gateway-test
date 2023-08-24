<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802135642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_sku ADD data4 VARCHAR(255) DEFAULT NULL, ADD data5 VARCHAR(255) DEFAULT NULL, ADD data6 VARCHAR(255) DEFAULT NULL, ADD data7 VARCHAR(255) DEFAULT NULL, ADD data8 VARCHAR(255) DEFAULT NULL, ADD data9 VARCHAR(255) DEFAULT NULL, ADD data10 VARCHAR(255) DEFAULT NULL, ADD data11 VARCHAR(255) DEFAULT NULL, ADD data12 VARCHAR(255) DEFAULT NULL, ADD data13 VARCHAR(255) DEFAULT NULL, ADD data14 VARCHAR(255) DEFAULT NULL, ADD data15 VARCHAR(255) DEFAULT NULL, ADD data16 VARCHAR(255) DEFAULT NULL, ADD data17 VARCHAR(255) DEFAULT NULL, ADD data18 VARCHAR(255) DEFAULT NULL, ADD data19 VARCHAR(255) DEFAULT NULL, ADD data20 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_sku DROP data4, DROP data5, DROP data6, DROP data7, DROP data8, DROP data9, DROP data10, DROP data11, DROP data12, DROP data13, DROP data14, DROP data15, DROP data16, DROP data17, DROP data18, DROP data19, DROP data20');
    }
}
