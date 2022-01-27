<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{

    public $implement = ['System.Behaviors.SettingsModel'];
    // A unique code
    public $settingsCode = 'odoo_rams_settings';
    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    // fields:

    // middle_name:
    //     label: 'Name'
    //     oc.commentPosition: ''
    //     span: auto
    //     type: text


    //
}
