<?php

namespace App\Services;

class TaskGlobalService
{
    public function computeScore($urgency, $impact, $effort)
    {
        $map = ['low' => 1, 'medium' => 2, 'high' => 3];

        return
            ($map[$urgency] * 2) +
            ($map[$impact] * 3) +
            ((4 - $map[$effort]) * 2);
    }
}
