<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707143846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx ADD sklbl_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE105E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('CREATE INDEX IDX_29E1AE105E25CDC ON sklbl_fx (sklbl_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE105E25CDC');
        $this->addSql('DROP INDEX IDX_29E1AE105E25CDC ON sklbl_fx');
        $this->addSql('ALTER TABLE sklbl_fx DROP sklbl_order_id');
    }
}
