<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/data.php';
    class HCDiscovery extends IPSModule
    {
        use CloudDataHelper;

        public function Create()
        {
            //Never delete this line!
            parent::Create();

            $this->ConnectParent('{D82190C8-2B57-A79E-57BA-FE24E81C01B2}');
        }

        public function Destroy()
        {
            //Never delete this line!
            parent::Destroy();
        }

        public function GetConfigurationForm()
        {
            $data = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

            if ($this->HasActiveParent()) {
                $result = $this->getData('/v1.0/plants');

                $Values = [];

                foreach ($result->plants as $plant) {
                    $AddValue = [
                        'name'            => 'Home Control ' . $plant->name,
                        'plantname'       => $plant->name,
                        'country'         => $plant->country,
                        'instanceID'      => $this->searchHCPlants($plant->id)
                    ];

                    $AddValue['create'] = [
                        [
                            'moduleID'      => '{13F1A7A2-8756-AA9F-9ECD-84976F618BE0}', // Konfigurator
                            'configuration' => [
                                'PlantID' => $plant->id
                            ]
                        ],
                        [
                            'moduleID'      => '{6D394B18-B31F-59C1-4A82-54DBDA8F30B6}', // Splitter
                            'configuration' => [
                                'PlantID' => $plant->id
                            ]
                        ]
                    ];
                    $Values[] = $AddValue;
                }
                $data['actions'][0]['values'] = $Values;
            }
            IPS_LogMessage('form', json_encode($data));
            return json_encode($data);
        }

        private function searchHCPlants($plantID)
        {
            $ids = IPS_GetInstanceListByModuleID('{13F1A7A2-8756-AA9F-9ECD-84976F618BE0}'); //HCConfigurator
            foreach ($ids as $id) {
                if (IPS_GetProperty($id, 'PlantID') == $plantID) {
                    return $id;
                }
            }
            return 0;
        }
    }