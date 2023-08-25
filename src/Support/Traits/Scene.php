<?php

namespace Rice\Basic\Support\Traits;

trait Scene
{
    public function prepareForValidation()
    {
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        $instance->setRules($this->getSceneRules());
    }

    /**
     * @return array
     */
    public function getSceneRules(): array
    {
        $rules        = $this->rules();
        $actionMethod = $this->route()->getActionMethod();

        $scenes = $this->scenes()[$actionMethod] ?? [];

        // 未定义默认未不进行规则校验
        if (empty($scenes)) {
            return [];
        }

        $newRules = [];
        foreach ($scenes as $k => $v) {
            if (is_numeric($k)) {
                $newRules[$v] = $rules[$v];

                continue;
            }
            $newRules[$k] = $v;
        }

        return $newRules;
    }
}
