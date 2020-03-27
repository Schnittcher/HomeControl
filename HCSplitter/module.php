<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
class HCSplitter extends IPSModule
{
    use CloudDataHelper;

    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->ConnectParent('{D82190C8-2B57-A79E-57BA-FE24E81C01B2}');
        $this->RegisterPropertyString('PlantID', '');
        $this->RegisterPropertyInteger('UpdateInterval', 0);

        $this->RegisterTimer('HC_UpdateStatus', 0, 'HC_UpdateStatus($_IPS[\'TARGET\']);');
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
        $this->SetTimerInterval('HC_UpdateStatus', $this->ReadPropertyInteger('UpdateInterval') * 1000);
    }

    public function UpdateStatus()
    {
        $Data['DataID'] = '{3F8F3831-5B8A-CA3C-E1F7-6E00748B977D}';
        $Data['Buffer'] = $this->getData('/v1.0/plants/' . $this->ReadPropertyString('PlantID'));

        $Data = json_encode($Data);
        $this->SendDataToChildren($Data);
    }

    public function ForwardData($JSONString)
    {
        $data = json_decode($JSONString, true);
        if (array_key_exists('Endpoint',$data)) {
            $data['DataID'] = '{31403531-143D-531C-BB5F-808F10D199B3}';
            $data['Endpoint'] = str_replace('{plantId}', $this->ReadPropertyString('PlantID'), $data['Endpoint']);
            $this->SendDebug(__FUNCTION__ . 'JSON', json_encode($data), 0);
            return $this->SendDataToParent(json_encode($data));
        }
        if (array_key_exists('Function',$data)) {
            switch ($data['Function']) {
                case 'updateScenes':
                    return $this->updateScenes();
                    break;
                }
        }
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Splitter RECV', utf8_decode($data->Buffer));

        //$this->SendDataToChildren(json_encode(['DataID' => '{3F8F3831-5B8A-CA3C-E1F7-6E00748B977D}', $data->Buffer]));
    }

    private function updateScenes()
    {
        $Data['DataID'] = '{3F8F3831-5B8A-CA3C-E1F7-6E00748B977D}';
        $Data['Buffer'] = $this->getData('/v1.0/scene/comfort/addressLocation/plants/'.$this->ReadPropertyString('PlantID'));

        $Data = json_encode($Data);
        $this->SendDataToChildren($Data);
    }
}