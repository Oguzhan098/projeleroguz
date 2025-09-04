<?php
class Flight {
    public $id;
    public $from_city;
    public $to_city;
    public $passenger_count;
    public $start_time;
    public $end_time;
    public $plane_id;
    public $airport_id;

    public function __construct($from_city, $to_city, $passenger_count, $start_time, $end_time, $plane_id, $airport_id, $id = null) {
        $this->from_city = $from_city;
        $this->to_city = $to_city;
        $this->passenger_count = $passenger_count;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->plane_id = $plane_id;
        $this->airport_id = $airport_id;
        $this->id = $id;
    }
}
