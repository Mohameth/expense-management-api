<?php

namespace App\Enum;

enum ExpenseType: string
{
    case FUEL       = 'essence';
    case TOLL       = 'peage';
    case MEAL       = 'repas';
    case CONFERENCE = 'conference';
}