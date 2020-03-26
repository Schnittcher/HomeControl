<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
    class HCDeviceLight extends IPSModule
    {
        use SplitterDataHelper;

        public function Create()
        {
            //Never delete this line!
            parent::Create();

            $this->ConnectParent('{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}');
            $this->RegisterPropertyString('ModuleID', '');

            $this->RegisterVariableBoolean('Reachable', $this->Translate('Reachable'), '~Switch', 0);
            $this->RegisterVariableBoolean('State', $this->Translate('State'), '~Switch', 0);
            $this->RegisterVariableInteger('Level', $this->Translate('Level'), '~Intensity.100', 0);
            $this->EnableAction('State');
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
                case 'State':
                        switch ($Value) {
                            case true:
                                $payload['status'] = 'on';
                                break;
                            case false:
                                $payload['status'] = 'off';
                                break;
                        }
                        $result = $this->postData('/v1.0/light/lighting/addressLocation/plants/{plantId}/modules/parameter/id/value/' . $this->ReadPropertyString('ModuleID'), json_encode($payload));
                        if ($result == '') {
                            $this->SetValue('State', $Value);
                        }
                        break;
                case 'Level':
                    $payload['level'] = $Value;
                    $result = $this->postData('/v1.0/light/lighting/addressLocation/plants/{plantId}/modules/parameter/id/value/' . $this->ReadPropertyString('ModuleID'), json_encode($payload));
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
            foreach ($data->modules->lights as $key => $light) {
                if ($light->sender->plant->module->id == $this->ReadPropertyString('ModuleID')) {
                    $this->SetValue('Reachable', $light->reachable);
                    $this->SetValue('Level', $light->level);
                    switch ($light->status) {
                        case 'on':
                            $this->SetValue('State', true);
                            break;
                        case 'off':
                            $this->SetValue('State', false);
                            break;
                    }
                }
            }
        }
    }