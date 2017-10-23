<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Container;

/**
 * Основной запрос для получения расчета
 *
 * @property int $type - default 0. Тип запроса. 1 - с котировкой
 * @property string $uid - GUID сообщения, используется техподдержкой для поиска информации о запросах и анализа ситуаций.
 * @property Policy $Policy
 * @property Prolongation $Prolongation - пролонгация
 */
class Request extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'in:0|1'],
        'uid' => ['toString', 'required', 'notEmpty'],
        'Policy' => ['container:' . Policy::class, 'required'],
        'Prolongation' => ['container:' . Prolongation::class],
    ];

    protected function init()
    {
        $this->set('type', 0);
    }

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type', 'uid']);
        $this->toXmlTags($xml, ['Policy', 'Prolongation']);

        return $this;
    }

    public function validate()
    {
        $errors = parent::validate();

        if (!$this->Prolongation) {
            //Ext validation for non-prolongation
            if ($this->Policy && $this->Policy->Participants === null) {
                $errors['Policy.Participants'][] = 'Is required';
            }

            if ($this->Policy && $this->Policy->ContractTerm) {
                $ContractTerm = $this->Policy->ContractTerm;
                if ($ContractTerm->ProgramType === null) {
                    $errors['Policy.ContractTerm.ProgramType'][] = 'Is required';
                }
                if ($ContractTerm->DurationMonth === null) {
                    $errors['Policy.ContractTerm.DurationMonth'][] = 'Is required';
                }
                if ($ContractTerm->PaymentType === null) {
                    $errors['Policy.ContractTerm.PaymentType'][] = 'Is required';
                }
                if ($ContractTerm->Purpose === null) {
                    $errors['Policy.ContractTerm.Purpose'][] = 'Is required';
                }
            }

            if ($this->Policy && $this->Policy->Vehicle) {
                $Vehicle = $this->Policy->Vehicle;
                if ($Vehicle->Manufacturer === null) {
                    $errors['Policy.Vehicle.Manufacturer'][] = 'Is required';
                }
                if ($Vehicle->Model === null) {
                    $errors['Policy.Vehicle.Model'][] = 'Is required';
                }
                if ($Vehicle->Year === null) {
                    $errors['Policy.Vehicle.Year'][] = 'Is required';
                }
                if ($Vehicle->Cost === null) {
                    $errors['Policy.Vehicle.Cost'][] = 'Is required';
                }
                if ($Vehicle->ManufacturerType === null) {
                    $errors['Policy.Vehicle.ManufacturerType'][] = 'Is required';
                }
                if ($Vehicle->Power === null) {
                    $errors['Policy.Vehicle.Power'][] = 'Is required';
                }
                if ($Vehicle->CarBodyType === null) {
                    $errors['Policy.Vehicle.CarBodyType'][] = 'Is required';
                }
            }
        }

        return $errors;
    }

    /**
     * Generate unique uid
     * @return $this
     */
    public function genUuid() {
        $this->uid = Utils::genUuid();
        return $this;
    }
}