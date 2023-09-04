<?php

namespace App\Http\Controllers;

use GPBMetadata\Google\Api\Auth;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private mixed $firebase;
    private $coursesCollection;
    private $driversCollection;

    public function __construct()
    {
        $this->firebase = app('firebase.firestore');
        $this->coursesCollection = $this->firebase->database()->collection('courses');
        $this->driversCollection = $this->firebase->database()->collection('drivers');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coursesDocuments = $this->coursesCollection->documents();

        if ($coursesDocuments->isEmpty()) {
            return view("admin.courses.index", ['courses' => collect()]);
        }

        $courses = collect($coursesDocuments->rows())->map(function ($document) {
            $data = $document->data();
            $data['id'] = $document->id();
            return $data;
        });

        return view('admin.courses.index',compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_client' => 'required|string',
            'tel_client' => 'required|string',
            'type_course_id' => 'required|integer',
            'type_trajet' => 'required|integer',
            'lieu_depart' => 'required|string',
            'destination' => 'required|string',
        ]);

        $driver = $this->firebase->database()->collection('drivers')
            ->where('car.type_course', '=', $request->type_course_id)
            ->where('tel', '=', $request->driver_tel)->documents()->rows();
        $driverId = $driver[0]->id();

        $client = $this->firebase->database()->collection('clients')->where('tel', '=', $request->tel_client)->documents()->rows();


        if (empty($client[0]->data())) {
            $client = $this->firebase->database()->collection('clients')->add([
                'nom' => $request->nom_client,
                'tel' => $request->tel_client,
                'course_id' => null,
                'user_id' => null,
                'wallet' => [
                    'amount' => 0,
                ]
            ]);
        }

        $course = $this->firebase->database()->collection('courses')->add([
            'client' => [
                'nom' => $client[0]->data()['nom'],
                'tel' => $client[0]->data()['tel'],
                'user_id' => "admin-".auth()->user()->id,
                'wallet' => [
                    'amount' => $client[0]->data()['wallet']['amount'],
                ]
            ],
            'date_debut' => date('Y-m-d H:i:s'),
            'date_end' => null,
            'driver' => null,
            'driver_id' => $driverId,
            'client_id' => $client[0]->id(),
            'emplacements' => [
                'firstPlace' => [
                    'coordinates' => [
                        'latitude' => $request->latitudeLieuDepart,
                        'latitudeDelta' => null,
                        'longitude' => $request->longitudeLieuDepart,
                        'longitudeDelta' => null,
                    ],
                    'description' => $request->lieu_depart,
                    'place_id' => "",
                ],
                'secondPlace' => [
                    'coordinates' => [
                        'latitude' => $request->latitudeDestination,
                        'latitudeDelta' => null,
                        'longitude' => $request->longitudeDestination,
                        'longitudeDelta' => null,
                    ],
                    'description' => $request->destination,
                    'place_id' => "",
                ]
            ],
            'price' => (float)$request->price,
            'kilometrage' => (float)$request->km,
            'distance' => $request->distance,
            'etat_course_id' => 1,
            'types_course_id' => (int)$request->type_course_id,
            'types_trajet_id' => (int)$request->type_trajet,
            'user_id' => "admin-".auth()->user()->id,
            'comment' => null,
            'timeWaiting' => $request->time_waiting,
            'raiting' => 0,
        ]);

        $driver = $this->firebase->database()->collection('drivers')->document($driverId);

        $driver->update([
            ['path' => 'course_id', 'value' => $course->id()]
        ]);

        return view('admin.courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get the prixKm of the specified resource from storage.
     */

    public function getPrixKm(int $typeCourseId, float $km)
    {
        $typeCourse = $this->firebase->database()->collection('types_courses')->where('id', '=', $typeCourseId)->documents()->rows();
        $prix_km = $typeCourse[0]->data()['prix_km'];
        $prix_km_unit = $typeCourse[0]->data()['prix_km_unit'];

        if ($prix_km * $km <= $prix_km) {
            $price = $prix_km;
        } else {
            $price = round($prix_km + ($prix_km_unit * ($km - 1)));
        }

        return response()->json(['price' => $price]);
    }

    public function getDrivers() {
        $driverDocuments = $this->driversCollection->documents();

        $drivers = collect($driverDocuments->rows())->map(function ($document) {
            $data = $document->data();

            $id = $document->id();
            $nom = $data['nom'];
            $prenom = $data['prenom'];
            $tel = $data['tel'];
            $latitude = $data['lat'];
            $longitude = $data['lng'];
            $car = $data['car'];

            $location = [
                'latitude' => $latitude,
                'longitude' => $longitude
            ];

            return [
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
                'tel' => $tel,
                'location' => $location,
                'car' => $car
            ];
        })->all();

        return response()->json(["drivers" => $drivers]);
    }
}
