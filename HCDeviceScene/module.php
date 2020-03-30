<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
    class HCDeviceScene extends IPSModule
    {
        use SplitterDataHelper;

        public function Create()
        {
            //Never delete this line!
            parent::Create();

            $this->ConnectParent('{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}');

            $this->RegisterAttributeString('Scenes', '');
            //Scene Profile for Groups
            if ($this->HasActiveParent()) {
                $this->updateScenes();
            } else {
                $ProfileName = 'HC.Scnees' . $this->InstanceID;
                if (!IPS_VariableProfileExists($ProfileName)) {
                    IPS_CreateVariableProfile($ProfileName, 1);
                }
            }
            $this->RegisterVariableInteger('Scenes', $this->Translate('Scenes'), 'HC.Scenes' . $this->InstanceID, 0);
            $this->SetValue('Scenes', -1);
            $this->EnableAction('Scenes');
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
            $this->SetReceiveDataFilter('.*scenes.*');
        }

        public function RequestAction($Ident, $Value)
        {
            switch ($Ident) {
                case 'Scenes':
                    $payload['enable'] = true;
                    $scenes = json_decode($this->ReadAttributeString('Scenes'), true);
                    $result = $this->postData('/v1.0/scene/comfort/addressLocation/plants/{plantId}/modules/parameter/id/value/' . $scenes[$Value]['id'], json_encode($payload));
                break;
            }
        }

        public function updateScenes()
        {
            $Data = [];

            $Data['DataID'] = '{D52C7F96-76A6-12BB-99EB-BE8D586BBAAA}';
            $Data['Function'] = 'updateScenes';

            $Data = json_encode($Data);
            $this->SendDataToParent($Data);
        }

        public function ReceiveData($JSONString)
        {
            $data = json_decode($JSONString)->Buffer;
            IPS_LogMessage('Scenes', print_r($data, true));

            $ProfileName = 'HC.Scenes' . $this->InstanceID;
            if (!IPS_VariableProfileExists($ProfileName)) {
                IPS_CreateVariableProfile($ProfileName, 1);
            } else {
                IPS_DeleteVariableProfile($ProfileName);
                IPS_CreateVariableProfile($ProfileName, 1);
            }
            $scenesAttribute = [];
            IPS_SetVariableProfileAssociation($ProfileName, -1, '-', '', 0x000000);
            foreach ($data->scenes as $key => $scene) {
                IPS_SetVariableProfileAssociation($ProfileName, $key, $scene->name, '', 0x000000);
                $scenesAttribute[$key]['name'] = $scene->name;
                $scenesAttribute[$key]['id'] = $scene->sender->plant->module->id;
            }
            IPS_SetVariableProfileIcon($ProfileName, 'Database');
            if (!empty($scenesAttribute)) {
                $this->WriteAttributeString('Scenes', json_encode($scenesAttribute));
            }
        }
    }