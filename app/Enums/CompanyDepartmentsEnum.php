<?php

namespace App\Enums;

enum CompanyDepartmentsEnum : string
{
    case C_SUITE = 'C-Suite';
    case ENGINEERING = 'Development/Engineering';
    case CREATIVE = 'Creative';
    case AD_OPS = 'Ad Operations';
    case DIGITAL = 'Digital Content';
    case SALES = 'Sales';
    case HR = 'Human Resources';
    case ACCOUNT = 'Account Management';
}
