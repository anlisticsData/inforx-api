<?php

namespace Interfaces\Settings;

use Models\Setting;

interface ISettingRepository
{
    function records(Setting $setting);
    function all();
    function oneSettingType($type);
    function created(Setting $setting);
    function update(Setting $setting);

}
