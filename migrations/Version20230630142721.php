<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630142721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl ADD article_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ofs_sklbl ADD CONSTRAINT FK_53587F7C7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE ofs_sklbl ADD CONSTRAINT FK_53587F7C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_53587F7C7294869C ON ofs_sklbl (article_id)');
        $this->addSql('CREATE INDEX IDX_53587F7C19EB6921 ON ofs_sklbl (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs_sklbl DROP FOREIGN KEY FK_53587F7C7294869C');
        $this->addSql('ALTER TABLE ofs_sklbl DROP FOREIGN KEY FK_53587F7C19EB6921');
        $this->addSql('DROP INDEX IDX_53587F7C7294869C ON ofs_sklbl');
        $this->addSql('DROP INDEX IDX_53587F7C19EB6921 ON ofs_sklbl');
        $this->addSql('ALTER TABLE ofs_sklbl DROP article_id, DROP client_id');
    }
}
