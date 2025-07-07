<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250706150809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE form (id SERIAL NOT NULL, respondent_id INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5288FD4FCE80CD19 ON form (respondent_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form ADD CONSTRAINT FK_5288FD4FCE80CD19 FOREIGN KEY (respondent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD form_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD CONSTRAINT FK_8F418EBF5FF69B7D FOREIGN KEY (form_id) REFERENCES form (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F418EBF5FF69B7D ON form_response (form_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP CONSTRAINT FK_8F418EBF5FF69B7D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form DROP CONSTRAINT FK_5288FD4FCE80CD19
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE form
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F418EBF5FF69B7D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP form_id
        SQL);
    }
}
