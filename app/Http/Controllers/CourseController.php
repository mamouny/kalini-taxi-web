<?php

namespace App\Http\Controllers;

use GPBMetadata\Google\Api\Auth;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;

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

        $courses = collect($coursesDocuments->rows())->map(function ($document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $driver = $this->firebase->database()->collection('drivers')->document($data['driver_id'])->snapshot()->data();
            $data['driver'] = $driver;

            return $data;
        });

        return view('admin.courses.index',compact('courses'));
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
            'driver_id' => 'required|string',
        ]);

        try {
            $driverId = $this->firebase->database()->collection('drivers')->document($request->driver_id)->snapshot()->id();

            $clientQuery = $this->firebase->database()->collection('clients')->where('tel', '=', $request->tel_client);
            $clientDocument = $clientQuery->documents()->rows();

            if (!empty($clientDocument)) {
                $client = $clientDocument[0];
            } else {
                $client = null;
            }

            if (!$client) {
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
                    'nom' => $client->data()['nom'],
                    'tel' => $client->data()['tel'],
                    'user_id' => "admin-".auth()->user()->id,
                    'wallet' => [
                        'amount' => $client->data()['wallet']['amount'],
                    ]
                ],
                'date_debut' => date('Y-m-d H:i:s'),
                'date_end' => null,
                'driver' => null,
                'driver_id' => $driverId,
                'client_id' => $client->id(),
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

            $clientFromDb = $this->firebase->database()->collection('clients')->document($client->id());

            $clientFromDb->update([
                ['path' => 'course_id', 'value' => $course->id()]
            ]);

            $driver->update([
                ['path' => 'course_id', 'value' => $course->id()]
            ]);
        } catch(FirebaseException $e){
            return redirect()->route("admin.courses")->with('error', 'Error while creating course.');
        }

        return redirect()->route("admin.courses")->with('success', 'Course created successfully.');
    }

    public function cancelCourse(string $id)
    {
        $course = $this->coursesCollection->document($id);

        $course->update([
            ['path' => 'etat_course_id', 'value' => 6],
            ['path' => 'date_end', 'value' => date('Y-m-d H:i:s')],
        ]);

        $course = $course->snapshot()->data();

        $this->extracted($course);

        return redirect()->route('admin.courses')->with('success', 'Course canceled successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $courseId)
    {
        $course = $this->coursesCollection->document($courseId)->snapshot()->data();

        $this->extracted($course);

        $this->coursesCollection->document($courseId)->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully');
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
        })->where("course_id",null)->all();

        return response()->json(["drivers" => $drivers]);
    }

    /**
     * @param $course
     * @return void
     */
    public function extracted($course): void
    {
        $driver = $this->firebase->database()->collection('drivers')->document($course['driver_id']);

        $driver->update([
            ['path' => 'course_id', 'value' => null]
        ]);

        $client = $this->firebase->database()->collection('clients')->document($course['client_id']);

        $client->update([
            ['path' => 'course_id', 'value' => null]
        ]);
    }
}
