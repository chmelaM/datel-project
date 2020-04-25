<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportEplan extends Model
{
    public $version;
    public $sourceId;
    public $page;

    public function hydrate($data)
    {
        $this->version = (string)$data['version'][0];
        $this->sourceId = (int)$data->Document['source_id'];
        $this->page = new Page($data->Document->Page);
    }
}

class Page
{
    public $sourceId;
    public $headers = [];
    public $columnHeaders = [];
    public $lines = [];
    public $footers = [];

    public function __construct($data)
    {

        $this->sourceId = (int)$data['source_id'];

        foreach ($data->Header->Property as $property)
                array_push($this->headers, new Property($property));

        foreach ($data->ColumnHeader as $columnHeader) {
           if (count($columnHeader->PropertyName)>1)
                for ($i = 0; $i < count($columnHeader->PropertyName); $i++)
                    array_push($this->columnHeaders, new ColumnHeader($columnHeader, $i));
            else
                array_push($this->columnHeaders, new ColumnHeader($columnHeader));
        }

        foreach ($data->Line as $line)
            array_push($this->lines, new Line($line));

        foreach ($data->Footer as $footer)
            array_push($this->footers, new Footer($footer));
    }

}

class Property
{
    public $formattingType;
    public $formattingLength;
    public $formattingRAlign;
    public $propertyName;
    public $propertyValue;

    public function __construct($data, $i = null)
    {
        $this->formattingType = (int)$data['FormattingType'];
        $this->formattingLength = (int)$data['FormattingLength'];
        $this->formattingRAlign = (int)$data['FormattingRAlign'];
        if ($i == null) {
            $this->propertyName = (string)$data->PropertyName;
            $this->propertyValue = (string)$data->PropertyValue;
        } else {
            $this->propertyName = (string)$data->PropertyName[$i];
            $this->propertyValue = (string)$data->PropertyValue[$i];
        }
    }
}

class ColumnHeader
{
    public $dataType;
    public $propertyName;

    public function __construct($data, $i=null)
    {
        $this->dataType = (string)$data['DataType'];
        if($i == null)
        $this->propertyName = (string)$data->PropertyName;
        else {
            $this->propertyName = (string)$data->PropertyName[$i];

        }
    }
}

class Line
{
    public $sourceId;
    public $separator;
    public $properties = [];

    public function __construct($data)
    {
        $this->sourceId = (string)$data['source_id'];
        $this->separator = (string)$data['separator'];

        foreach ($data->Label->Property as $property)
            if (count($property->PropertyName)>1)
                for ($i = 0; $i < count($property->PropertyName); $i++)
                    array_push($this->properties, new Property($property, $i));
            else
                array_push($this->properties, new Property($property));
    }
}

class Footer{
    public function __construct($data)
    {
    }
}
