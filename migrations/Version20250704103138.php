<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704103138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE form_response (id SERIAL NOT NULL, question_id INT NOT NULL, text_response VARCHAR(500) DEFAULT NULL, numeric_response INT DEFAULT NULL, picked_options TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F418EBF1E27F6BF ON form_response (question_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN form_response.picked_options IS '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD CONSTRAINT FK_8F418EBF1E27F6BF FOREIGN KEY (question_id) REFERENCES field (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP CONSTRAINT FK_8F418EBF1E27F6BF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE form_response
        SQL);
    }
}
