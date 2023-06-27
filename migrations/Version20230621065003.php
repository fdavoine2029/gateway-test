<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621065003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_sup ADD delivery_note NUMERIC(10, 0) DEFAULT NULL, ADD batch_num VARCHAR(30) DEFAULT NULL, ADD receiv_qte NUMERIC(12, 3) NOT NULL, ADD comment LONGTEXT DEFAULT NULL, ADD status INT NOT NULL, ADD blmod VARCHAR(4) NOT NULL, ADD delay_trsp DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_sup DROP delivery_note, DROP batch_num, DROP receiv_qte, DROP comment, DROP status, DROP blmod, DROP delay_trsp');
    }
}
