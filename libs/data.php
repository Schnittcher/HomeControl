<?php

declare(strict_types=1);
trait CloudDataHelper
{
    private function getData($endpoint, $plantid = '')
    {
        return $this->checkResult(json_decode($this->SendDataToParent(json_encode([
            'DataID'   => '{31403531-143D-531C-BB5F-808F10D199B3}',
            'PlantID'  => $plantid,
            'Endpoint' => $endpoint,
            'Payload'  => ''
        ]))));
    }

    private function postData($endpoint, $plantid = '', $payload = '{}')
    {
        return $this->checkResult(json_decode($this->SendDataToParent(json_encode([
            'DataID'   => '{31403531-143D-531C-BB5F-808F10D199B3}',
            'PlantID'  => $plantid,
            'Endpoint' => $endpoint,
            'Payload'  => $payload
        ]))));
    }

    private function checkResult($result)
    {
        if (isset($result->errors)) {
            throw new Exception($result->errors->internalMessage);
        }

        return $result;
    }
}

trait SplitterDataHelper
{
    private function getData($endpoint)
    {
        $endpoint = str_replace('{plantId}', $this->ReadPropertyString('PlantID'), $endpoint);
        $this->SendDebug('getData Endpoint', $endpoint, 0);
        return $this->checkResult(json_decode($this->SendDataToParent(json_encode([
            'DataID'   => '{D52C7F96-76A6-12BB-99EB-BE8D586BBAAA}',
            'Endpoint' => $endpoint,
            'Payload'  => ''
        ]))));
    }

    private function postData($endpoint, $payload = '{}')
    {
        $this->SendDebug('postData Endppoint', $endpoint, 0);
        $this->SendDebug('postData Payload', $payload, 0);
        return $this->checkResult(json_decode($this->SendDataToParent(json_encode([
            'DataID'   => '{D52C7F96-76A6-12BB-99EB-BE8D586BBAAA}',
            'Endpoint' => $endpoint,
            'Payload'  => $payload
        ]))));
    }

    private function checkResult($result)
    {
        if (isset($result->errors)) {
            throw new Exception($result->errors[0]->internalMessage);
        }

        return $result;
    }
}