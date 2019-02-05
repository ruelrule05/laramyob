<?php

namespace Creativecurtis\Laramyob\Models\Remote;

use Creativecurtis\Laramyob\Models\BaseModel as Model;

class Myob extends Model
{
    //Base URL for company file is default so we override
    public $endpoint = '';
    public $data;

    public function __construct($endpoint, $data) {
        parent::__construct();
        $this->endpoint = $endpoint;
        $this->data = $data;
    }
}