<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Gram\SurveyBundle\Entity\QuestionType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Version20161204110039 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var  ContainerInterface */
    private $container;

    public function up(Schema $schema)
    {
        $code = $this->container->getParameter('active_survey_promocode');

        $this->addSql('INSERT INTO survey_bundle_question_type (name, `key`) VALUES
            (\'Несколько вариантов ответа\', \'' . QuestionType::MULTIPLE_OPTIONS . '\'),
            (\'Один вариант ответа\', \'' . QuestionType::SIGLE_OPTION . '\')');

        $this->addSql('INSERT INTO survey_bundle_survey (name, create_date, promocode) VALUES
            (\'Анкета-заявка на получение ознакомительного образца «СмартСмайл»\', now(), \'' . $code . '\')');

        $this->addSql('INSERT INTO survey_bundle_question (type_id, survey_id, name, ordering, is_responent_answer_allowed) VALUES
              ((SELECT id
                FROM survey_bundle_question_type
                WHERE `key` = \'' . QuestionType::SIGLE_OPTION . '\'),
               (SELECT id
                FROM survey_bundle_survey
                WHERE promocode = \'' . $code . '\'), \'Кто планирует использовать образец?\', 1, TRUE),
              ((SELECT id
                FROM survey_bundle_question_type
                WHERE `key` = \'' . QuestionType::SIGLE_OPTION . '\'),
               (SELECT id
                FROM survey_bundle_survey
                WHERE promocode = \'' . $code . '\'), \'Источник информации о Программе\', 2, TRUE)
        ');
        $this->addSql('INSERT INTO survey_bundle_choice (name, can_terminate_survey) VALUES
              (\'Я лично\', FALSE),
              (\'Реклама в БЦ "Центрбудинвест" (Киев, ул. Глубочицкая, 17д)\', FALSE),
              (\'Реклама в БЦ "Палладин Сити" (Киев, ул. Антоновича, 172)\', FALSE)');

        $this->addSql('INSERT INTO survey_bundle_question_join_choice (choice_id, question_id) VALUES
              ((SELECT id
                FROM survey_bundle_choice
                WHERE name = \'Я лично\'),
               (SELECT id
                FROM survey_bundle_question
                WHERE name = \'Кто планирует использовать образец?\')),

              ((SELECT id
                FROM survey_bundle_choice
                WHERE name = \'Реклама в БЦ "Центрбудинвест" (Киев, ул. Глубочицкая, 17д)\'),
               (SELECT id
                FROM survey_bundle_question
                WHERE name = \'Источник информации о Программе\')),

              ((SELECT id
                FROM survey_bundle_choice
                WHERE name = \'Реклама в БЦ "Палладин Сити" (Киев, ул. Антоновича, 172)\'),
               (SELECT id
                FROM survey_bundle_question
                WHERE name = \'Источник информации о Программе\'))');
    }

    public function down(Schema $schema)
    {
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
