<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250705181151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD template_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP text_response
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP numeric_response
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP picked_options
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD CONSTRAINT FK_8F418EBF5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F418EBF5DA0FB8 ON form_response (template_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP CONSTRAINT FK_8F418EBF5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F418EBF5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD text_response VARCHAR(500) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD numeric_response INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD picked_options TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP template_id
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN form_response.picked_options IS '(DC2Type:array)'
        SQL);
    }
}
