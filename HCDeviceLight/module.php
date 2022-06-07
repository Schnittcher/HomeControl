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
            $this->SetReceiveDataFilter('.*' . $this->ReadPropertyString('ModuleID') . '.*');
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
            $JSONString = '{"DataID":"{3F8F3831-5B8A-CA3C-E1F7-6E00748B977D}","Buffer":{"modules":{"lights":[{"reachable":true,"status":"off","level":63,"fw":57,"consumptions":[{"unit":"watt","value":0,"timestamp":"2022-06-02T12:24:11+00:00"}],"sender":{"plant":{"module":{"id":"000000047422841000047400008219d1"}}}}],"plugs":[{"reachable":true,"status":"on","consumptions":[{"unit":"watt","value":0,"timestamp":"2022-06-02T12:24:11+00:00"}],"sender":{"plant":{"module":{"id":"000000047422841000047400000ae3e5"}}},"fw":68}],"automations":[],"energymeters":[],"remotes":[{"reachable":false,"battery":"full","sender":{"plant":{"module":{"id":"00000004742284100004740000a92629"}}},"fw":50},{"reachable":false,"battery":"full","sender":{"plant":{"module":{"id":"0000000474228410000474000099d93c"}}},"fw":50}],"heaters":[]}}}';
            $data = json_decode($JSONString)->Buffer;

            $this->SendDebug('JSON', $JSONString, 0);
            foreach ($data->modules->lights as $key => $light) {
                if ($light->sender->plant->module->id == $this->ReadPropertyString('ModuleID')) {
                    $this->SetValue('Reachable', $light->reachable);
                    if (property_exists($light, 'level')) {
                        $this->SetValue('Level', $light->level);
                    }
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