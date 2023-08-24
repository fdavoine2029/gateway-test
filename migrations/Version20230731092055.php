<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731092055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 ADD data2 VARCHAR(255) DEFAULT NULL, ADD data3 VARCHAR(255) DEFAULT NULL, DROP of_num, DROP sku, DROP redirect_url, CHANGE sku_tisse data1 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 ADD of_num INT NOT NULL, ADD sku VARCHAR(255) NOT NULL, ADD sku_tisse VARCHAR(255) DEFAULT NULL, ADD redirect_url VARCHAR(255) NOT NULL, DROP data1, DROP data2, DROP data3');
    }
}
