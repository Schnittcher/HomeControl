<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
    class HCDevicePlug extends IPSModule
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
            $this->RegisterVariableFloat('Consumptions', $this->Translate('Consumptions'), '~Watt.14490', 0);

            $this->EnableAction('State');
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
                        $result = $this->postData('/v1.0/plug/energy/addressLocation/plants/{plantId}/modules/parameter/id/value/' . $this->ReadPropertyString('ModuleID'), json_encode($payload));
                        if ($result == '') {
                            $this->SetValue('State', $Value);
                        }
                        break;
            }
        }

        public function ReceiveData($JSONString)
        {
            $data = json_decode($JSONString)->Buffer;

            $this->SendDebug('JSON', $JSONString, 0);
            foreach ($data->modules->plugs as $key => $plug) {
                if ($plug->sender->plant->module->id == $this->ReadPropertyString('ModuleID')) {
                    $this->SetValue('Reachable', $plug->reachable);
                    $this->SetValue('Consumptions', $plug->consumptions[0]->value);
                    switch ($plug->status) {
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