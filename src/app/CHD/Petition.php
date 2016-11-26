<?php

namespace App\CHD;

class Petition
{
    protected $petitionHTML;
    protected $id;

    public function get($id)
    {
        $this->id = $id;
        $data     = app('Request')->get(env('CHD_HOST').env('CHD_PETITION_URL').$id);
        //$data = app('Request')->get('http://localhost/test/pet_816.html797');

        $startString = '<div id="PRINT_EPETITION_DETAIL">';

        $start = strpos($data, $startString) + strlen($startString);
        $stop  = strpos($data, '<div class="contentType3Items">');

        $this->petitionHTML = trim(
            substr(
                $data,
                $start,
                $stop - $start
            )
        );

        return [
            'name'             => $this->name(),
            'description'      => $this->description(),
            'paper_signatures' => $this->paperSignatures(),
            'status'           => $this->status(),
        ];
    }

    protected function name()
    {
        $namePattern = '/<span class="subject_header">(?P<name>[^<]*)<\/span>/';
        if (!preg_match($namePattern, $this->petitionHTML, $name)) {
            throw new Exception('Couldn\'t find the name of petition ID '.$this->id);
        }

        return trim($name['name']);
    }

    protected function description()
    {
        $descriptionPattern = '/<span class="subject_header">(?:[^<]*)<\/span>(?:\ -)?(?P<description>[^<]*)<br\/> <\/div>/';
        if (!preg_match($descriptionPattern, $this->petitionHTML, $description)) {
            throw new Exception('Couldn\'t find the description of petition ID '.$this->id);
        }

        return trim($description['description']);
    }

    protected function paperSignatures()
    {
        $paperSignaturesPattern = '/<span class="property_name">Signatures papier(?::\ )<\/span>(?:\ )<span class="property_value">(?P<paper_signatures>\d+)<\/span>/';
        if (!preg_match($paperSignaturesPattern, $this->petitionHTML, $paper_signatures)) {
            return null;
        }

        return (int) $paper_signatures['paper_signatures'];
    }

    protected function status()
    {
        $statusPattern = '/" > <span class="property_value">(?P<status>[^<]*)/';
        if (!preg_match($statusPattern, $this->petitionHTML, $status)) {
            throw new Exception('Couldn\'t find the status of petition ID '.$this->id);
        }

        return trim($status['status']);
    }
}
