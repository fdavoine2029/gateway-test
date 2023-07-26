<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230721142336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC7CA59D21A');
        $this->addSql('DROP INDEX IDX_74B4FC7CA59D21A ON sklbl_fx2');
        $this->addSql('ALTER TABLE sklbl_fx2 CHANGE sklbl_custfile_id_id sklbl_custfile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC71B40BBA6 FOREIGN KEY (sklbl_custfile_id) REFERENCES sklbl_files (id)');
        $this->addSql('CREATE INDEX IDX_74B4FC71B40BBA6 ON sklbl_fx2 (sklbl_custfile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC71B40BBA6');
        $this->addSql('DROP INDEX IDX_74B4FC71B40BBA6 ON sklbl_fx2');
        $this->addSql('ALTER TABLE sklbl_fx2 CHANGE sklbl_custfile_id sklbl_custfile_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC7CA59D21A FOREIGN KEY (sklbl_custfile_id_id) REFERENCES sklbl_files (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_74B4FC7CA59D21A ON sklbl_fx2 (sklbl_custfile_id_id)');
    }
}
