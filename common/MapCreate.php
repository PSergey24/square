<?php

namespace app\common;

use Yii;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Event;
use dosamigos\google\maps\controls\MapTypeControlOptions;
use dosamigos\google\maps\controls\MapTypeControlStyle;
use dosamigos\google\maps\controls\ControlPosition;
use app\models\Court;
use yii\db\Query;

class MapCreate
{

    protected $map_width;

    protected $map_height;

    protected $city;

    const SPB_city_name = 'SPB';
    const Moscow_city_name = 'MSK';
    const MSK_lat = 55.7384694;
    const MSK_lng = 37.6420348;
    const SPB_LAT = 59.910326;
    const SPB_LNG = 30.3185942;

    public function __construct($width, $height, $city)
    {
        $this->map_width = $width;
        $this->map_height = $height;
        $this->city = $city;
    }

    /*
     * @return Map $map|Exception
     */
    public function create()
    {
        switch ($this->city) {
            case self::SPB_city_name:
                $coord = Yii::createObject([
                    'class' => LatLng::className(),
                    'lat' => self::SPB_LAT,
                    'lng' => self::SPB_LNG
                ]);
                break;
            case self::Moscow_city_name:
                $coord = Yii::createObject([
                    'class' => LatLng::className(),
                    'lat' => self::MSK_lat,
                    'lng' => self::MSK_lng
                ]);

                break;
            default:
                throw new \yii\base\Exception('City ' . $this->city . ' is not supported');
        }

        $map = Yii::createObject([
            'class' => Map::className(),
            'center' => $coord,
            'zoom' => 11,
        ]);

        $this->setSize($map);


        $marker = Yii::createObject([
                'class' => Marker::className(),
                'position' => $coord,
                'draggable' => true,
                'title' => 'Введите название площадки'
            ]);
        
        $marker_event = Yii::createObject([
            'class' => Event::className(),
            'trigger' => 'dragend',
            'js' => "
                var lat = gmarker1.getPosition().lat();
                var lng = gmarker1.getPosition().lng();
                //set hidden input's value
                $('#court-lat').val(lat);
                $('#court-lon').val(lng);
                //decode marker position coordinates to address string
                var geocoder = new google.maps.Geocoder;
                var latlng = {lat: lat, lng: lng};
                geocoder.geocode({'location': latlng}, function(results, status) {
                    if (status === 'OK') {
                        //set address string to input 
                        $('#court-address').val(results[0].formatted_address);
//                        console.log(results);
                    }
                });
                
                if ($('#court-name').val() != '')
                    $('#court_description').text($('#court-name').val());
               
                ",
            ]);

        $marker->addEvent($marker_event);
        
        $marker->attachInfoWindow(
            new InfoWindow([
                'content' => '<p id="court_description">Введите название площадки</p>',
            ])
        );

        $map->addOverlay($marker);

        return $map;

    }

    public function create_all_points($sport_type = 'all')
    {
        $map_coord = Yii::createObject([
            'class' => LatLng::className(),
            'lat' => self::SPB_LAT,
            'lng' => self::SPB_LNG
        ]);

        $map = Yii::createObject([
            'class' => Map::className(),
            'center' => $map_coord,
            'zoom' => 11,
        ]);

        $this->setSize($map);

        return $map;

    }


    /*
     * Set map widht and height
     * @param dosamigos\google\maps\Map $map
     */

    public function setSize(Map $map) {
        $map->height = $this->map_height;
        $map->width = $this->map_width;
        return $map;
    }
}