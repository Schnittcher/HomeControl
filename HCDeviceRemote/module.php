<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
    class HCDeviceRemote extends IPSModule
    {
        use SplitterDataHelper;

        public function Create()
        {
            //Never delete this line!
            parent::Create();

            $this->ConnectParent('{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}');
            $this->RegisterPropertyString('ModuleID', '');

            $this->RegisterVariableBoolean('Reachable', $this->Translate('Reachable'), '~Switch', 0);
            $this->RegisterVariableString('Battery', $this->Translate('Battery'), '', 0);
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

        public function ReceiveData($JSONString)
        {
            $data = json_decode($JSONString)->Buffer;

            $this->SendDebug('JSON', $JSONString, 0);
            foreach ($data->modules->remotes as $key => $remote) {
                if ($remote->sender->plant->module->id == $this->ReadPropertyString('ModuleID')) {
                    $this->SetValue('Reachable', $remote->reachable);
                    $this->SetValue('Battery', $this->Translate($remote->battery));
                }
            }
        }
    }