<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250706162244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP CONSTRAINT fk_8f418ebf5da0fb8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP CONSTRAINT fk_8f418ebfce80cd19
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_8f418ebf5da0fb8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_8f418ebfce80cd19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP respondent_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP template_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD respondent_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD template_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD CONSTRAINT fk_8f418ebf5da0fb8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD CONSTRAINT fk_8f418ebfce80cd19 FOREIGN KEY (respondent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_8f418ebf5da0fb8 ON form_response (template_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_8f418ebfce80cd19 ON form_response (respondent_id)
        SQL);
    }
}
