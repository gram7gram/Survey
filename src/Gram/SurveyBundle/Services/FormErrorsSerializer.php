<?php

namespace Gram\SurveyBundle\Services;

use Symfony\Component\Form\FormInterface;

/**
 * @see https://gist.github.com/Graceas/6505663
 */
class FormErrorsSerializer
{

    public function serializeFormErrors(FormInterface $form, $flat_array = false, $add_form_name = false, $glue_keys = '_')
    {
        $errors = array();
        $errors['messages'] = array();
        $errors['errors'] = array();

        foreach ($form->getErrors() as $error) {
            $errors['messages'][] = $error->getMessage();
        }

        $errors['errors'] = $this->serialize($form);

        if ($flat_array) {
            $errors['errors'] = $this->arrayFlatten($errors['errors'],
                $glue_keys, (($add_form_name) ? $form->getName() : ''));
        }


        return $errors;
    }

    private function serialize(FormInterface $form)
    {
        $local_errors = array();
        foreach ($form->getIterator() as $key => $child) {

            foreach ($child->getErrors() as $error) {
                $local_errors[$key] = $error->getMessage();
            }

            if (count($child->getIterator()) > 0) {
                $local_errors[$key] = $this->serialize($child);
            }
        }

        return $local_errors;
    }

    private function arrayFlatten($array, $separator = "_", $flattened_key = '')
    {
        $flattenedArray = array();
        foreach ($array as $key => $value) {

            if (is_array($value)) {

                $flattenedArray = array_merge($flattenedArray,
                    $this->arrayFlatten($value, $separator,
                        (strlen($flattened_key) > 0 ? $flattened_key . $separator : "") . $key)
                );

            } else {
                $flattenedArray[(strlen($flattened_key) > 0 ? $flattened_key . $separator : "") . $key] = $value;
            }
        }
        return $flattenedArray;
    }

}