<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230804085259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_logs ADD sklbl_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_logs ADD CONSTRAINT FK_AF112FD75E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('CREATE INDEX IDX_AF112FD75E25CDC ON sklbl_logs (sklbl_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_logs DROP FOREIGN KEY FK_AF112FD75E25CDC');
        $this->addSql('DROP INDEX IDX_AF112FD75E25CDC ON sklbl_logs');
        $this->addSql('ALTER TABLE sklbl_logs DROP sklbl_order_id');
    }
}
