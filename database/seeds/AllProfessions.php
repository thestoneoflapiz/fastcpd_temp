<?php

use App\{Profession};
use Illuminate\Database\Seeder;

class AllProfessions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $professions = array(
            [ 
                "title" => "Accountancy",
                "url" => "accountancy",
                "cpd_requirements" => json_encode([
                    "a" => 30,
                    "b" => 5,
                    "c" => 5,
                    "flex" => 80,
                ]),
            ],
            [ 
                "title" => "Aeronautical Engineering",
                "url" => "aeronautical-engineering",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Agricultural & Biosystems Engineering",
                "url" => "agricultual-biosystems-engineering",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Agriculture",
                "url" => "agriculture",
                "cpd_requirements" => json_encode([
                    "a" => 45,
                    "b" => 35,
                    "c" => 25,
                ]),
            ],
            [ 
                "title" => "Chemical Engineering",
                "url" => "chemical-engineering",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Chemist",
                "url" => "chemist",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Chemical Laboratory Technician",
                "url" => "chemical-laboratory-technician",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Civil Engineering",
                "url" => "civil-engineering",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Criminology",
                "url" => "criminology",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Custom Brokers",
                "url" => "custom-brokers",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Dentist",
                "url" => "dentist",
                "cpd_requirements" => json_encode([
                    "flex" => 60,
                ]),
            ],
            [ 
                "title" => "Dental Hygienist",
                "url" => "dental-hygienist",
                "cpd_requirements" => json_encode([
                    "flex" => 60,
                ]),
            ],
            [ 
                "title" => "Dental Technologist",
                "url" => "dental-technologist",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Professional Electrical Engineer",
                "url" => "profession-electrical-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Registered Electrical Engineer",
                "url" => "registered-electrical-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Registered Master Electrician",
                "url" => "registered-master-electrician",
                "cpd_requirements" => json_encode([
                    "flex" => 30,
                ]),
            ],
            [ 
                "title" => "Professional Electronics Engineer",
                "url" => "professional-electronics-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Electronics Technician",
                "url" => "electronics-technician",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Environmental Planning",
                "url" => "environmental-planning",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Fisheries",
                "url" => "fisheries",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Forestry",
                "url" => "forestry",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Geodetic Engineering",
                "url" => "geodetic-engineering",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Geology",
                "url" => "geology",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Guidance & Counseling",
                "url" => "guidance-counseling",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Interior Designer",
                "url" => "interior-designer",
                "cpd_requirements" => json_encode([
                    "a" => 45,
                    "b" => 35,
                    "c" => 25,
                ]),
            ],
            [ 
                "title" => "Landscape Architecture",
                "url" => "landscape-architecture",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Librarian",
                "url" => "librarian",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Master Plumbing",
                "url" => "master-plumbing",
                "cpd_requirements" => json_encode([
                    "flex" => 30,
                ]),
            ],
            [ 
                "title" => "Professional Mechanical Engineer",
                "url" => "professional-mechanical-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Registered Merchanical Engineer",
                "url" => "registered-mechanical-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Certifide Plant Mechanic",
                "url" => "certifide-plant-mechanic",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Medical Technologists",
                "url" => "medical-technologists",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Medical Laboratory Technician",
                "url" => "medical-laboratory-technician",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Medicine",
                "url" => "medicine",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Metallurgical Engineer",
                "url" => "metallurgical-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Midwifery",
                "url" => "midwifery",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Mining Engineer",
                "url" => "mining-engineer",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Naval Architecture",
                "url" => "naval-architecture",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Nursing",
                "url" => "nursing",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Nutrition & Dietetics",
                "url" => "nutrition-dietetics",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Optomery",
                "url" => "optomery",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Pharmacy",
                "url" => "pharmacy",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Physical Theraphy",
                "url" => "physical-theraphy",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Psychologist",
                "url" => "psychologist",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Psychometrician",
                "url" => "psychometrician",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Occupational Therapist",
                "url" => "occupational-therapist",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Professional Teachers",
                "url" => "professional-teachers",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Respiratory Theraphist",
                "url" => "respiratory-theraphist",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Radiologic Technologist",
                "url" => "radiologic-technologist",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "X-Ray Technician",
                "url" => "xray-technician",
                "cpd_requirements" => json_encode([
                    "flex" => 30,
                ]),
            ],
            [ 
                "title" => "Consultant",
                "url" => "consultant",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Appraiser",
                "url" => "appraiser",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Real Estate Broker",
                "url" => "real-estate-broker",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Salesperson",
                "url" => "salesperson",
                "cpd_requirements" => json_encode([
                    "flex" => 15,
                ]),
            ],
            [ 
                "title" => "Sanitary Engineering",
                "url" => "sanitary-engineering",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Social Work",
                "url" => "social-work",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
            [ 
                "title" => "Veterinary Medicine",
                "url" => "veterinary-medicine",
                "cpd_requirements" => json_encode([
                    "flex" => 45,
                ]),
            ],
        );
        Profession::insert($professions);      
    }
}
