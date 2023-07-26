<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726131000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP opt_data1, DROP opt_data2, DROP opt_data3, DROP opt_data4, DROP opt_data5, DROP opt_data6, DROP opt_data7, DROP opt_data8, DROP opt_data9, DROP opt_data10');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP opt_data1, DROP opt_data2, DROP opt_data3, DROP opt_data4, DROP opt_data5, DROP opt_data6, DROP opt_data7, DROP opt_data8, DROP opt_data9, DROP opt_data10');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx ADD opt_data1 VARCHAR(255) DEFAULT NULL, ADD opt_data2 VARCHAR(255) DEFAULT NULL, ADD opt_data3 VARCHAR(255) DEFAULT NULL, ADD opt_data4 VARCHAR(255) DEFAULT NULL, ADD opt_data5 VARCHAR(255) DEFAULT NULL, ADD opt_data6 VARCHAR(255) DEFAULT NULL, ADD opt_data7 VARCHAR(255) DEFAULT NULL, ADD opt_data8 VARCHAR(255) DEFAULT NULL, ADD opt_data9 VARCHAR(255) DEFAULT NULL, ADD opt_data10 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD opt_data1 VARCHAR(255) DEFAULT NULL, ADD opt_data2 VARCHAR(255) DEFAULT NULL, ADD opt_data3 VARCHAR(255) DEFAULT NULL, ADD opt_data4 VARCHAR(255) DEFAULT NULL, ADD opt_data5 VARCHAR(255) DEFAULT NULL, ADD opt_data6 VARCHAR(255) DEFAULT NULL, ADD opt_data7 VARCHAR(255) DEFAULT NULL, ADD opt_data8 VARCHAR(255) DEFAULT NULL, ADD opt_data9 VARCHAR(255) DEFAULT NULL, ADD opt_data10 VARCHAR(255) DEFAULT NULL');
    }
}
