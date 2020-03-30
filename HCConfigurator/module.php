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

            $this->SendDebug('Topology', print_r($result, true), 0);
            IPS_LogMessage('Topology', print_r($result, true));

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
            $GUIDs['remote'] = '{0463735C-76BA-7822-A69A-618C1B7E69DE}';
            $GUIDs['scenes'] = '{7FD91366-6D85-C2AA-3A3E-22D097B48826}';
            $location = 0;
            $AddValueAmbient[] = [
                'id'                    => 9999,
                'name'                  => 'Ambients',
                'DisplayName'           => $this->Translate('Ambients'),
                'hwtype'                => '',
                'device'                => ''
            ];
            foreach ($result->plant->ambients as $keyAmbient => $ambient) {
                $AddValueAmbient[] = [
                    'parent'                => 9999,
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
            $AddValueAmbient[] = [
                'id'                    => 9998,
                'name'                  => 'Others',
                'DisplayName'           => $this->Translate('Others'),
                'hwtype'                => '',
                'device'                => ''
            ];
            foreach ($result->plant->modules as $keyModule => $module) {
                $AddValueAmbient[] = [
                    'parent'                => 9998,
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
            $AddValueAmbient[] = [
                'id'                    => 9997,
                'name'                  => 'Scenes',
                'DisplayName'           => $this->Translate('Scenes'),
                'hwtype'                => '',
                'device'                => '',
                'instanceID'            => $this->searchHCDeviceScenes(),
                'create'                => [
                    [
                        'moduleID'      => $GUIDs['scenes'],
                        'configuration' => [],
                        'location'      => $location
                    ],
                    [
                        'moduleID'      => '{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}', // Splitter
                        'configuration' => [
                            'PlantID' => $result->plant->id
                        ]
                    ]
                ]
            ];

            $data['actions'][0]['values'] = $AddValueAmbient;
            return json_encode($data);
        }
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
    }

    private function searchHCDevice($moduleID)
    {
        //Plugs & Lights
        $ids = array_merge(IPS_GetInstanceListByModuleID('{653D8C04-9A82-CC2A-50A8-6CEC33E0DCFB}'), IPS_GetInstanceListByModuleID('{4862376D-E80B-E1D6-EAA8-CB182580CC02}'));
        //Plugs & Lights & Automations
        $ids = array_merge($ids, IPS_GetInstanceListByModuleID('{D2A177BB-1F33-9F78-9FC9-EDC22FDAE7DA}'));
        //Plugs & Lights & Automations & Remote
        $ids = array_merge($ids, IPS_GetInstanceListByModuleID('{0463735C-76BA-7822-A69A-618C1B7E69DE}'));
        foreach ($ids as $id) {
            if (IPS_GetProperty($id, 'ModuleID') == $moduleID) {
                return $id;
            }
        }
        return 0;
    }

    private function searchHCDeviceScenes()
    {
        $ids = IPS_GetInstanceListByModuleID('{7FD91366-6D85-C2AA-3A3E-22D097B48826}');
        return $ids[0];
    }
}