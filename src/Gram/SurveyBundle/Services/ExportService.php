<?php

namespace Gram\SurveyBundle\Services;

use Gram\SurveyBundle\Entity\Answer;
use Gram\SurveyBundle\Entity\AnswerChoice;
use Gram\SurveyBundle\Entity\CompletedSurvey;
use Gram\SurveyBundle\Entity\Survey;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExportService
{

    /** @var ContainerInterface */
    private $container;

    /** @param ContainerInterface $container */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function generateExcelExport(array $survey)
    {
        $phpexcel = $this->container->get('phpexcel');
        $trans = $this->container->get('translator');

        $excel = $phpexcel->createPHPExcelObject();
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->mergeCells('A1:C1');
        $excel->getActiveSheet()->mergeCells('A2:C2');
        $excel->getActiveSheet()->mergeCells('A3:C3');

        $sheet = $excel->getActiveSheet();
        $sheet->setShowSummaryBelow(false);

        $sheet->getColumnDimensionByColumn(0)->setWidth(60);
        $sheet->getColumnDimensionByColumn(1)->setWidth(30);
        $sheet->getColumnDimensionByColumn(2)->setWidth(30);

        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(15);
        $sheet->getRowDimension(3)->setRowHeight(15);
        $sheet->getRowDimension(3)->setRowHeight(20);

        $row = 1;
        $sheet->setCellValueExplicitByColumnAndRow(0, $row, $trans->trans('Survey report title', [], 'GramSurveyBundle') . ': ' . (new \DateTime())->format('d.m.Y H:i'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow(0, $row)->getFont()->setBold(true);

        $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $trans->trans('Survey report respondent count', [], 'GramSurveyBundle') . ': ' . $survey['countRespondents'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $survey['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $trans->trans('Survey report question answer', [], 'GramSurveyBundle'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValueExplicitByColumnAndRow(1, $row, $trans->trans('Survey report answer count', [], 'GramSurveyBundle'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValueExplicitByColumnAndRow(2, $row, $trans->trans('Survey report answer percent', [], 'GramSurveyBundle'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $row = 0;

        $sheet->setCellValueExplicitByColumnAndRow(1, ++$row, $survey['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValueExplicitByColumnAndRow(1, ++$row, $survey['description'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValueExplicitByColumnAndRow(1, ++$row, $survey['countRespondents'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        if (isset($survey['questions']) && $survey['questions']) {
            ++$row;
            foreach ($survey['questions'] as $question) {
                $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $question['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
                $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyleByColumnAndRow(0, $row)->getFont()->setBold(true);

                if ($question['description']) {
                    $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $question['description'], \PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyleByColumnAndRow(0, $row)->getFont()->setItalic(true);
                }

                if (isset($question['choices']) && $question['choices']) {
                    foreach ($question['choices'] as $index => $choice) {
                        $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $choice['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setWrapText(true);

                        $sheet->setCellValueExplicitByColumnAndRow(1, $row, $choice['count'], \PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                        $sheet->setCellValueExplicitByColumnAndRow(2, $row, $choice['percent'] . '%', \PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    }
                }
            }
        }

        return $excel;
    }

    public function generateExtendedExcelExport(Survey $survey, $statistic, $entities)
    {
        $countQuestionChoices = $survey->getCountChoices();
        $phpexcel = $this->container->get('phpexcel');
        $trans = $this->container->get('translator');

        $excel = $phpexcel->createPHPExcelObject();
        $excel->setActiveSheetIndex(0);

        $column = 1;
        $row = 1;

        $excel->getActiveSheet()->mergeCellsByColumnAndRow($column, 1, $countQuestionChoices + 1, 1);
        $excel->getActiveSheet()->mergeCellsByColumnAndRow($column, 2, $countQuestionChoices + 1, 2);
        $excel->getActiveSheet()->mergeCellsByColumnAndRow($column, 3, $countQuestionChoices + 1, 3);
        $excel->getActiveSheet()->mergeCellsByColumnAndRow($column, 4, $countQuestionChoices + 1, 4);

        $sheet = $excel->getActiveSheet();
        $sheet->setShowSummaryBelow(false);

        $sheet->getColumnDimensionByColumn(0)->setAutoSize(true);

        for ($i = 1; $i <= $countQuestionChoices; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setWidth(15);
        }

        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(15);
        $sheet->getRowDimension(2)->setRowHeight(15);

        $sheet->setCellValueExplicitByColumnAndRow($column, $row, $trans->trans('Survey report title', [], 'GramSurveyBundle') . ': ' . (new \DateTime())->format('d.m.Y H:i'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);

        $sheet->setCellValueExplicitByColumnAndRow($column, ++$row, $trans->trans('Survey report respondent count', [], 'GramSurveyBundle') . ': ' . $statistic['countRespondents'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValueExplicitByColumnAndRow($column, ++$row, $statistic['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValueExplicitByColumnAndRow($column, ++$row, $trans->trans('Survey report question answer', [], 'GramSurveyBundle'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        if (isset($statistic['questions']) && $statistic['questions']) {
            $row++;
            $leftShift = $column;
            foreach ($statistic['questions'] as $question) {
                $countChoices = count($question['choices']);

                if (isset($question['choices']) && $question['choices']) {
                    $excel->getActiveSheet()->mergeCellsByColumnAndRow($leftShift, $row, $leftShift + $countChoices - 1, $row);
                    $sheet->setCellValueExplicitByColumnAndRow($leftShift, $row, $question['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->getStyleByColumnAndRow($leftShift, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyleByColumnAndRow($leftShift, $row)->getFont()->setBold(true);

                    foreach ($question['choices'] as $index => $choice) {
                        $sheet->setCellValueExplicitByColumnAndRow($leftShift + $index, $row + 1, $choice['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet->getStyleByColumnAndRow($leftShift + $index, $row + 1)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyleByColumnAndRow($leftShift + $index, $row + 1)->getAlignment()->setWrapText(true);
                    }

                    $leftShift += $countChoices;
                }
            }

            $excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $column - 1, $row);

            $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $trans->trans('Survey report respondent', [], 'GramSurveyBundle'), \PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        /** @var CompletedSurvey $completedSurvey */
        foreach ($entities as $completedSurvey) {
            $individual = $completedSurvey->getUser()->getIndividual();

            $sheet->setCellValueExplicitByColumnAndRow(0, ++$row, $individual->getFullName(), \PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $answerChoices = [];
            /** @var Answer $answer */
            foreach ($completedSurvey->getAnswers() as $answer) {
                /** @var AnswerChoice $answerChoice */
                foreach ($answer->getChoices() as $answerChoice) {
                    $choice = $answerChoice->getChoice();
                    $answerChoices[$answer->getQuestion()->getId()][$choice->getId()] = true;
                }
            }

            if (isset($statistic['questions']) && $statistic['questions']) {
                $leftShift = $column;
                foreach ($statistic['questions'] as $question) {

                    if (isset($question['choices']) && $question['choices']) {
                        foreach ($question['choices'] as $index => $choice) {
                            if (isset($answerChoices[$question['id']]) && isset($answerChoices[$question['id']][$choice['id']])) {
                                $sheet->setCellValueExplicitByColumnAndRow($leftShift, $row, '+', \PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyleByColumnAndRow($leftShift, $row)->getAlignment()->setWrapText(true);
                                $sheet->getStyleByColumnAndRow($leftShift, $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                $sheet->getStyleByColumnAndRow($leftShift, $row)->getFont()->setBold(true);
                            }

                            ++$leftShift;
                        }
                    }
                }
            }
        }

        return $excel;
    }

    public function saveReport($excel)
    {
        $root = $this->container->getParameter('kernel.root_dir') . '/files';
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $hashName = md5(uniqid());
        $file = $root . '/' . $hashName . '.xlsx';
        $objWriter->save($file);

        return $file;
    }
}