<?php

namespace Gram\SurveyBundle\Command;

use Gram\SurveyBundle\Classes\SurveyStatistic;
use Gram\SurveyBundle\Entity\ChoiceRepository;
use Gram\SurveyBundle\Entity\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SurveyReportExportCommand extends ContainerAwareCommand
{

    const NAME = 'survey:generate:report';

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDefinition([
                new InputOption('survey', null, InputOption::VALUE_REQUIRED, 'Survey\'s id'),
                new InputOption('jms-job-id', null, InputOption::VALUE_OPTIONAL)
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $survey = $input->getOption('survey');

        if (!$survey) {
            throw new \Exception("[ error ] param survey id is required");
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $exportService = $this->getContainer()->get('survey.service_export');
        /** @var SurveyRepository $surveyReppo */
        $surveyReppo = $em->getRepository('GramSurveyBundle:Survey');
        /** @var ChoiceRepository $choiceRepo */
        $choiceRepo = $em->getRepository('GramSurveyBundle:Choice');

        $entity = $surveyReppo->getOrderedSurveyById($survey, false);

        if (!$entity) {
            throw new \Exception("[ error ] survey does not exist");
        }

        $surveyStatistic = new SurveyStatistic();
        $statistic = $surveyStatistic->countStatisticForQuestion($entity,
            $choiceRepo->countAnswerStatistic($survey));

        $excel = $exportService->generateExcelExport($statistic);

        $file = $exportService->saveReport($excel);

        $result = [
            'file' => $file,
            'hash' => md5(file_get_contents($file))
        ];

        $output->writeln(json_encode($result));
    }
}