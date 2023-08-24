<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731073110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config ADD f1_csv_num INT DEFAULT NULL, ADD f2_csv_num INT DEFAULT NULL, ADD f3_csv_num INT DEFAULT NULL, ADD f4_csv_num INT DEFAULT NULL, ADD f5_csv_num INT DEFAULT NULL, ADD customer_csv_num INT DEFAULT NULL, ADD lisage_csv_num INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config DROP f1_csv_num, DROP f2_csv_num, DROP f3_csv_num, DROP f4_csv_num, DROP f5_csv_num, DROP customer_csv_num, DROP lisage_csv_num');
    }
}
