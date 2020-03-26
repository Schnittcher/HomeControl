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
            $GUIDs['automation'] = '-';
            $location = 0;
            foreach ($result->plant->ambients as $keyAmbient => $ambient) {
                $AddValueAmbient[] = [
                    'id'                    => $keyAmbient + 1,
                    'ModuleID'		            => $ambient->id,
                    'name'                  => $ambient->name,
                    'DisplayName'           => $ambient->name,
                    'hwtype' 				           => '',
                    'device'                => ''
                ];
                foreach ($result->plant->ambients[$keyAmbient]->modules as $keyModule => $module) {
                    $AddValueAmbient[] = [
                        'parent'				            => $keyAmbient + 1,
                        'id'                    => 0,
                        'ModuleID'              => $module->id,
                        'name'                  => $module->name,
                        'DisplayName'           => $module->name,
                        'hwtype'                => $module->hw_type,
                        'device'                => $module->device,
                        'create'				            => [
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

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
    }
}