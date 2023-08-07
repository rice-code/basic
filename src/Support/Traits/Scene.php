<?php

namespace Rice\Basic\Support\Traits;

trait Scene
{
    public function prepareForValidation()
    {
        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        $instance->setRules($this->getSceneRules());
    }

    /**
     *
     * @return array
     */
    public function getSceneRules(): array
    {
        $rules = $this->rules();
        $actionMethod = $this->route()->getActionMethod();
        if ($scenes = $this->scenes()[$actionMethod] ?? []) {
            $newRules = [];
            foreach ($scenes as $k => $v) {
                if (is_numeric($k)) {
                    $newRules[$v] = $rules[$v];
                    continue;
                }
                $newRules[$k] = $v;
            }
            $rules = $newRules;
        }

        return $rules;
    }
}
