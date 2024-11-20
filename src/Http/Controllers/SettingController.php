<?php

namespace Esa\Helper\Http\Controllers;

use Esa\Helper\Models\AppSetting;

class SettingController
{
    public function index()
    {
        $settings = AppSetting::get()->groupBy('group');

        foreach ($settings as $group => $groups) {
            echo $group . "#";
            foreach ($groups as $setting) {
                echo '<pre>';
                var_dump($setting->value);
                echo '</pre>';
            }
        }
    }
}
