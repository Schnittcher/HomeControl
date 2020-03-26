<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
class HCConfigurator extends IPSModule
{
    use SplitterDataHelper;

    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->ConnectParent('{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}');
        $this->RegisterPropertyString('PlantID', '');
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function GetConfigurationForm()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        if ($this->ReadPropertyString('PlantID') == '') {
            return json_encode($data);
        }

        if ($this->HasActiveParent()) {
            $result = $this->getData('/v1.0/plants/{plantId}/topology');
            if (empty($result)) {
                return json_encode($data);
            }

            //$result = $this->getData('/v1.0/plants/' . $this->ReadPropertyString('PlantID').'/topology');
            IPS_LogMessage('Result PlantID', print_r($result, true));

            $Values = [];
            $ValuesAll = [];

            $GUIDs['plug'] = '{653D8C04-9A82-CC2A-50A8-6CEC33E0DCFB}';
            $GUIDs['light'] = '{4862376D-E80B-E1D6-EAA8-CB182580CC02}';
            $GUIDs['automation'] = '{D2A177BB-1F33-9F78-9FC9-EDC22FDAE7DA}';
            $location = 0;
            foreach ($result->plant->ambients as $keyAmbient => $ambient) {
                $AddValueAmbient[] = [
                    'id'                    => $keyAmbient + 1,
                    'name'                  => $ambient->name,
                    'DisplayName'           => $ambient->name,
                    'hwtype'                => '',
                    'device'                => ''
                ];
                foreach ($result->plant->ambients[$keyAmbient]->modules as $keyModule => $module) {
                    $AddValueAmbient[] = [
                        'parent'                => $keyAmbient + 1,
                        'id'                    => 0,
                        'name'                  => $module->name,
                        'DisplayName'           => $module->name,
                        'hwtype'                => $module->hw_type,
                        'device'                => $module->device,
                        'instanceID'            => $this->searchHCDevice($module->id),
                        'create'                => [
                            [
                                'moduleID'      => $GUIDs[$module->device],
                                'configuration' => [
                                    'ModuleID'    => $module->id
                                ],
                                'location' => $location
                            ],
                            [
                                'moduleID'      => '{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}', // Splitter
                                'configuration' => [
                                    'PlantID' => $result->plant->id
                                ]
                            ]
                        ]
                    ];
                }
            }
            $data['actions'][0]['values'] = $AddValueAmbient;
            return json_encode($data);
        }
    }

    private function searchHCDevice($moduleID)
    {
        //Plugs & Lights
        $ids = array_merge(IPS_GetInstanceListByModuleID('{653D8C04-9A82-CC2A-50A8-6CEC33E0DCFB}'),IPS_GetInstanceListByModuleID('{4862376D-E80B-E1D6-EAA8-CB182580CC02}'));
        //Plugs & Lights $ Automations
        $ids = array_merge($ids,IPS_GetInstanceListByModuleID('{D2A177BB-1F33-9F78-9FC9-EDC22FDAE7DA}'));
        foreach ($ids as $id) {
            if (IPS_GetProperty($id, 'ModuleID') == $moduleID) {
                return $id;
            }
        }
        return 0;
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
    }
}