<?php
// User class
class UserLocation {
    use Model;

    protected $table = 'PersonAddress';
    protected $order_column = "pid";
    protected $allowedColumns = [
        'pid',
        'province',
        'district',
        'city',
        'address'
    ];

    public $errors = [];

    public function validateLocation($data) {
        $this->errors = [];

        // Validate province
        if (isset($data['province'])) {
            if (empty($data['province']) || !preg_match('/^[a-zA-Z\s]+$/', $data['province'])) {
                $this->errors['province'] = 'Province is not valid';
            }
        }

        // Validate district
        if (isset($data['district'])) {
            if (empty($data['district']) || !preg_match('/^[a-zA-Z\s]+$/', $data['district'])) {
                $this->errors['district'] = 'District is not valid';
            }
        }

        // Validate city
        if (isset($data['city'])) {
            if (empty($data['city']) || !preg_match('/^[a-zA-Z\s]+$/', $data['city'])) {
                $this->errors['city'] = 'City is not valid';
            }
        }

        // Validate address
        if (isset($data['address'])) {
            if (empty($data['address']) || strlen($data['address']) < 5 ) {
                $this->errors['address'] = 'Valid address required';
            }
        }

        // Check if locations match (e.g., city belongs to district, district belongs to province)
        if (!empty($data['province']) && !empty($data['district']) && !empty($data['city'])) {
            if (!$this->locationsMatch($data['province'], $data['district'], $data['city'])) {
                $this->errors['location'] = 'The provided city, district, and province do not match';
            }
        }

        // Return true if no errors, otherwise false
        return empty($this->errors);
    }

    private function locationsMatch($province, $district, $city) {
        // Sri Lanka-specific location hierarchy
        $validLocations = [
            'Western' => [
                'Colombo' => ['Colombo', 'Dehiwala-Mount Lavinia', 'Moratuwa', 'Battaramulla', 'Sri Jayawardenepura Kotte', 'Nugegoda', 'Maharagama', 'Kesbewa', 'Kaduwela', 'Kolonnawa', 'Homagama', 'Piliyandala', 'Boralesgamuwa', 'Malabe', 'Pita Kotte', 'Avissawella', 'Padukka', 'Hanwella'],
                'Gampaha' => ['Negombo', 'Gampaha', 'Ja-Ela', 'Wattala', 'Ragama', 'Kadawatha', 'Minuwangoda', 'Kiribathgoda', 'Hendala', 'Mabole', 'Katunayake', 'Kandana', 'Nittambuwa', 'Kirindiwela', 'Biyagama', 'Peliyagoda'],
                'Kalutara' => ['Kalutara', 'Beruwala', 'Panadura', 'Aluthgama', 'Wadduwa', 'Matugama', 'Bandaragama', 'Horana', 'Ingiriya', 'Bulathsinhala', 'Agalawatta']
            ],
            'Central' => [
                'Kandy' => ['Kandy', 'Peradeniya', 'Gampola', 'Katugastota', 'Nawalapitiya', 'Pilimathalawa', 'Wattegama', 'Akurana', 'Digana', 'Gelioya', 'Kundasale', 'Galagedara', 'Hanguranketa', 'Ampitiya'],
                'Matale' => ['Matale', 'Dambulla', 'Sigiriya', 'Ukuwela', 'Rattota', 'Galewela', 'Nalanda', 'Palapathwela', 'Aluvihare', 'Yatawatta'],
                'Nuwara Eliya' => ['Nuwara Eliya', 'Hatton', 'Talawakele', 'Maskeliya', 'Pussellawa', 'Kotagala', 'Lindula', 'Ragala', 'Walapane', 'Hanguranketha']
            ],
            'Southern' => [
                'Galle' => ['Galle', 'Hikkaduwa', 'Unawatuna', 'Ambalangoda', 'Karapitiya', 'Baddegama', 'Bentota', 'Balapitiya', 'Elpitiya', 'Imaduwa', 'Batapola', 'Ahangama', 'Ahungalla'],
                'Matara' => ['Matara', 'Weligama', 'Dickwella', 'Akuressa', 'Kamburupitiya', 'Hakmana', 'Deniyaya', 'Mirissa', 'Devinuwara', 'Malimboda', 'Morawaka'],
                'Hambantota' => ['Hambantota', 'Tangalle', 'Tissamaharama', 'Kataragama', 'Ambalantota', 'Beliatta', 'Weeraketiya', 'Lunugamvehera', 'Sooriyawewa', 'Angunukolapelessa']
            ],
            'Northern' => [
                'Jaffna' => ['Jaffna', 'Nallur', 'Chavakachcheri', 'Point Pedro', 'Karainagar', 'Velanai', 'Valvettithurai', 'Kopay', 'Kaithady', 'Manipay', 'Tellippalai', 'Chunnakam', 'Uduvil'],
                'Kilinochchi' => ['Kilinochchi', 'Pallai', 'Paranthan', 'Karachchi', 'Mulankavil', 'Pooneryn', 'Kandavalai'],
                'Mullaitivu' => ['Mullaitivu', 'Puthukudiyiruppu', 'Oddusuddan', 'Thunukkai', 'Mallavi', 'Mankulam'],
                'Vavuniya' => ['Vavuniya', 'Cheddikulam', 'Nedunkeni', 'Omanthai'],
                'Mannar' => ['Mannar', 'Adampan', 'Nanattan', 'Musali', 'Madhu']
            ],
            'Eastern' => [
                'Trincomalee' => ['Trincomalee', 'Kinniya', 'Mutur', 'Kantale', 'Nilaveli', 'China Bay', 'Seruwila', 'Thampalakamam', 'Kuchchaveli', 'Gomarankadawala'],
                'Batticaloa' => ['Batticaloa', 'Eravur', 'Valachchenai', 'Kalkudah', 'Oddamavadi', 'Vakarai', 'Kattankudy', 'Chenkalady', 'Araipattai'],
                'Ampara' => ['Ampara', 'Kalmunai', 'Sammanthurai', 'Dehiattakandiya', 'Uhana', 'Pottuvil', 'Akkaraipattu', 'Sainthamaruthu', 'Thirukkovil', 'Nintavur', 'Addalachchenai', 'Mahaoya']
            ],
            'North Western' => [
                'Kurunegala' => ['Kurunegala', 'Kuliyapitiya', 'Maho', 'Polgahawela', 'Pannala', 'Narammala', 'Nikaweratiya', 'Wariyapola', 'Ibbagamuwa', 'Alawwa', 'Giriulla', 'Bingiriya'],
                'Puttalam' => ['Puttalam', 'Chilaw', 'Wennappuwa', 'Anamaduwa', 'Nattandiya', 'Dankotuwa', 'Kalpitiya', 'Marawila', 'Madampe', 'Arachchikattuwa', 'Norochcholai']
            ],
            'North Central' => [
                'Anuradhapura' => ['Anuradhapura', 'Kekirawa', 'Medawachchiya', 'Mihintale', 'Thambuttegama', 'Eppawala', 'Kahatagasdigiliya', 'Galenbindunuwewa', 'Horowpothana', 'Kebithigollewa', 'Rambewa', 'Thalawa'],
                'Polonnaruwa' => ['Polonnaruwa', 'Kaduruwela', 'Hingurakgoda', 'Medirigiriya', 'Dimbulagala', 'Manampitiya', 'Lankapura', 'Elahera', 'Bakamuna', 'Jayanthipura']
            ],
            'Uva' => [
                'Badulla' => ['Badulla', 'Bandarawela', 'Ella', 'Hali-Ela', 'Welimada', 'Mahiyanganaya', 'Diyatalawa', 'Haputale', 'Passara', 'Lunugala', 'Uva-Paranagama', 'Kandaketiya'],
                'Monaragala' => ['Monaragala', 'Wellawaya', 'Bibile', 'Buttala', 'Kataragama', 'Siyambalanduwa', 'Thanamalvila', 'Badalkumbura', 'Madulla']
            ],
            'Sabaragamuwa' => [
                'Ratnapura' => ['Ratnapura', 'Balangoda', 'Embilipitiya', 'Pelmadulla', 'Kuruwita', 'Eheliyagoda', 'Kalawana', 'Kahawatta', 'Rakwana', 'Opanayaka', 'Godakawela', 'Nivithigala'],
                'Kegalle' => ['Kegalle', 'Mawanella', 'Warakapola', 'Rambukkana', 'Galigamuwa', 'Deraniyagala', 'Yatiyantota', 'Ruwanwella', 'Dehiowita', 'Aranayaka', 'Hemmathagama']
            ]
        ];

        return isset($validLocations[$province][$district]) && in_array($city, $validLocations[$province][$district]);
    }

   
}
