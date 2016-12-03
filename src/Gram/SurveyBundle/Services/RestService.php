<?php

namespace Gram\SurveyBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class RestService
{
    /** @var ContainerInterface */
    private $container;

    /** @var EntityManager */
    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function handleRequestAndForm(Request $request, Form $formType = null)
    {
        $this->handleRequest($request, $formType);
        if ($formType) {
            $this->removeExtraFields($request, $formType);
            $formType->handleRequest($request);
        }
    }

    /**
     * @param Request $request
     * @param Form $formType
     * @return Request
     */
    public function handleRequest(Request $request, Form $formType = null)
    {
        $requestParameters = [];
        $content = $request->getContent();

        if (!empty($content)) {
            $params = json_decode($content, true);
            foreach ($params as $key => $value) {
                if ($value !== null) {
                    $requestParameters[$key] = $value;
                }
            }
        }

        if ($formType) {
            $request->request->set($formType->getName(), $requestParameters);
        } else {
            $request->request->replace($requestParameters);
        }

        return $request;
    }

    /**
     * @param Request $request
     * @param Form $form
     *
     * @return Request $request
     */
    public function removeExtraFields(Request $request, Form $form)
    {
        $data = $request->request->all();
        $children = array_keys($form->all());
        $newData = [];
        if (isset($data[$form->getName()])) {
            foreach ($data[$form->getName()] as $field => $value) {
                if (in_array($field, $children)) {
                    $newData[$form->getName()][$field] = $value;
                }
            }
        }

        $request->request->replace($newData);

        return $request;
    }

    /**
     * @deprecated see rest.form_errors_serializer
     *
     * @param Form $form
     * @param bool $trigger
     * @param null $index
     * @return array
     */
    public function parseFormErrors(Form $form, $trigger = true, $index = null)
    {
        $data = [];
        $messages = [];

        if ($trigger) {
            foreach ($form->getErrors() as $error) {
                $messages[] = $error->getMessage();
            }
        }

        /** @var Form $item */
        foreach ($form->all() as $key => $item) {
            $itemData = [];
            $index = is_numeric($index) ? $index : $key;
            if ($item->all()) {
                $itemData = $this->parseFormErrors($item, false, $index);
            } else {
                foreach ($item->getErrors() as $err) {
                    $itemData[] = $err->getMessage();
                }
            }

            if ($itemData) {
                if (is_numeric($index)) {
                    $data = array_merge($data, $itemData);
                } else {
                    $data[$item->getName()] = $itemData;
                }
            }
        }

        if ($trigger) {
            return [
                'messages' => $messages,
                'errors' => $data
            ];
        } else {
            return $data;
        }
    }

}
