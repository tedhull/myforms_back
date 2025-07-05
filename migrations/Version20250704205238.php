<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704205238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD respondent_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response ADD CONSTRAINT FK_8F418EBFCE80CD19 FOREIGN KEY (respondent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F418EBFCE80CD19 ON form_response (respondent_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP CONSTRAINT FK_8F418EBFCE80CD19
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F418EBFCE80CD19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_response DROP respondent_id
        SQL);
    }
}
