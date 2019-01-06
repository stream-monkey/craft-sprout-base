<?php

namespace barrelstrength\sproutbase\app\import\importers\fields;

use barrelstrength\sproutbase\app\import\base\FieldImporter;
use barrelstrength\sproutbase\SproutBase;
use craft\fields\RadioButtons as RadioButtonsField;

class RadioButtons extends FieldImporter
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return RadioButtonsField::class;
    }

    /**
     * @return mixed
     */
    public function getMockData()
    {
        $settings = $this->model->settings;

        $radioValue = '';

        if (!empty($settings['options'])) {
            $options = $settings['options'];

            $radioValue = SproutBase::$app->fieldImporter->getRandomOptionValue($options);
        }

        return $radioValue;
    }
}
