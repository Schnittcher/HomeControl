<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
    class HCDeviceAutomations extends IPSModule
    {
        use SplitterDataHelper;

        public function Create()
        {
            //Never delete this line!
            parent::Create();

            $this->ConnectParent('{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}');
            $this->RegisterPropertyString('ModuleID', '');

            $this->RegisterVariableBoolean('Reachable', $this->Translate('Reachable'), '~Switch', 0);
            $this->RegisterVariableInteger('Level', $this->Translate('Level'), '~Shutter', 0);
            $this->EnableAction('Level');
        }

        public function Destroy()
        {
            //Never delete this line!
            parent::Destroy();
        }

        public function ApplyChanges()
        {
            //Never delete this line!
            parent::ApplyChanges();
        }

        public function RequestAction($Ident, $Value)
        {
            switch ($Ident) {
                case 'Level':
                    $payload['level'] = $Value;
                    $result = $this->postData('/v1.0/automation/automation/addressLocation/plants/{plantId}/modules/parameter/id/value/' . $this->ReadPropertyString('ModuleID'), json_encode($payload));
                    if ($result == '') {
                        $this->SetValue('Level', $Value);
                    }
                    break;
            }
        }

        public function ReceiveData($JSONString)
        {
            $data = json_decode($JSONString)->Buffer;

            $this->SendDebug('JSON', $JSONString, 0);
            foreach ($data->modules->automations as $key => $automation) {
                if ($automations->sender->plant->module->id == $this->ReadPropertyString('ModuleID')) {
                    $this->SetValue('Reachable', $automations->reachable);
                    $this->SetValue('Level', $automations->level);
                }
            }
        }
    }