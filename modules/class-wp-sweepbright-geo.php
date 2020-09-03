<?php

/**
 * WP_SweepBright_Geo.
 *
 * This class contains geolocation sorting
 * data.
 *
 * @package    WP_SweepBright_Geo
 */

class WP_SweepBright_Geo {
	public $R = 6371;

	public function __construct() {
	}

	public function distance_between_points_rad($lat1, $lng1, $lat2, $lng2){
		$x = ($lng2-$lng1) * cos(($lat1+$lat2)/2);
		$y = ($lat2-$lat1);
		return sqrt($x*$x + $y*$y) * $this->R;
	}
	
	public function get_destination_lat_rad($lat1, $lng1, $d, $brng){
		return asin(sin($lat1)*cos($d/$this->R) +
			cos($lat1)*sin($d/$this->R)*cos($brng));
	}
	
	public function get_destination_lng_rad($lat1, $lng1, $d, $brng){
		$lat2 = $this->get_destination_lat_rad($lat1, $lng1, $d, $brng);
			return $lng1 + atan2(sin($brng)*sin($d/$this->R)*cos($lat1),
				cos($d/$this->R)-sin($lat1)*sin($lat2));
	}
	
	public function get_bounding_box_rad($lat, $lng, $range){
		$latmin = $this->get_destination_lat_rad($lat, $lng, $range, 0);
		$latmax = $this->get_destination_lat_rad($lat, $lng, $range, deg2rad(180));
		$lngmax = $this->get_destination_lng_rad($lat, $lng, $range, deg2rad(90));
		$lngmin = $this->get_destination_lng_rad($lat, $lng, $range, deg2rad(270));
		return array($latmin, $latmax, $lngmin, $lngmax);
	}
	
	public function distance_between_points_deg($lat1, $lng1, $lat2, $lng2){
		return $this->distance_between_points_rad(
			deg2rad($lat1), deg2rad($lng1), deg2rad($lat2), deg2rad($lng2));
	}
	
	public function get_bounding_box_deg($lat, $lng, $range){
		return array_map('rad2deg',
			$this->get_bounding_box_rad(deg2rad($lat), deg2rad($lng), $range));
	}

}
