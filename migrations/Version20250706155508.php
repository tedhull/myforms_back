<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250706155508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE form ADD template_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form ADD CONSTRAINT FK_5288FD4F5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5288FD4F5DA0FB8 ON form (template_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form DROP CONSTRAINT FK_5288FD4F5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5288FD4F5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form DROP template_id
        SQL);
    }
}
