<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        if(!$this->check_internet_connection()){
            return redirect()->route("admin.courses")->with('error', 'Vérifiiez votre connexion internet !');
        }

        $coursesDocuments = $this->coursesCollection->documents();

        $courses = collect($coursesDocuments->rows())->map(function ($document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $driver = $this->firebase->database()->collection('drivers')->document($data['driver_id'])->snapshot()->data();
            $data['driver'] = $driver;

            return $data;
        })->sortByDesc('date_debut');

        return view('admin.courses.index',compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_client' => 'required|string',
            'tel_client' => 'required|string',
            'type_course_id' => 'required|integer',
            'type_trajet' => 'required|integer',
            'lieu_depart' => 'required|string',
            'destination' => 'string|required_if:type_trajet,==,1|nullable',
            'driver_id' => 'required|string',
        ], [
            'nom_client.required' => 'Le nom du client est obligatoire.',
            'tel_client.required' => 'Le numéro de téléphone du client est obligatoire.',
            'type_trajet.required' => 'Le type de trajet est obligatoire.',
            'lieu_depart.required' => 'Le lieu de départ est obligatoire.',
            'driver_id.required' => 'Complétez les infos pour qu\'on puisse trouvez un chauffeur.',
        ]);

        try {

            // check if internet is available or not
            if(!$this->check_internet_connection()){
                return redirect()->route("admin.courses")->with('error', 'Vérifiiez votre connexion internet !');
            }

            $driverId = $this->firebase->database()->collection('drivers')->document($request->driver_id)->snapshot()->id();

            $clientQuery = $this->firebase->database()->collection('clients')->where('tel', '=', $request->tel_client);
            $clientDocument = $clientQuery->documents()->rows();

            if (!empty($clientDocument)) {
                // Client exists, retrieve the data and ID
                $client = $clientDocument[0];
                $clientId = $client->id();
                $clientData = $client->data();
            } else {
                // Client doesn't exist, create a new client and retrieve its ID
                $newClientData = [
                    'nom' => $validatedData['nom_client'],
                    'tel' => $validatedData['tel_client'],
                    'course_id' => null,
                    'user_id' => null,
                    'wallet' => [
                        'amount' => 0,
                    ]
                ];

                // Add the new client to the 'clients' collection
                $newClientDocument = $this->firebase->database()->collection('clients')->add($newClientData);
                $clientId = $newClientDocument->id();

                // Retrieve the newly created client data
                $clientData = $newClientDocument->snapshot()->data();
            }

            if ($validatedData['type_trajet'] == 2) {
                $destination = $validatedData['lieu_depart'];
                $destinationLatitude = $request->latitudeLieuDepart;
                $destinationLongitude = $request->longitudeLieuDepart;
            } else {
                $destination = $validatedData['destination'];
                $destinationLatitude = $request->latitudeDestination;
                $destinationLongitude = $request->longitudeDestination;
            }

            // Create a new course
            $course = $this->firebase->database()->collection('courses')->add([
                'client' => [
                    'nom' => $clientData['nom'],
                    'tel' => $clientData['tel'],
                    'user_id' => "admin-".auth()->user()->id,
                    'wallet' => [
                        'amount' => $clientData['wallet']['amount'],
                    ]
                ],
                'date_debut' => Carbon::now()->format('Y-m-d H:i:s'),
                'date_end' => null,
                'driver' => null,
                'driver_id' => $driverId,
                'client_id' => $clientId,
                'emplacements' => [
                    'firstPlace' => [
                        'coordinates' => [
                            'latitude' => $request->latitudeLieuDepart,
                            'latitudeDelta' => null,
                            'longitude' => $request->longitudeLieuDepart,
                            'longitudeDelta' => null,
                        ],
                        'description' => $validatedData['lieu_depart'],
                        'place_id' => "",
                    ],
                    'secondPlace' => [
                        'coordinates' => [
                            'latitude' => $destinationLatitude,
                            'latitudeDelta' => null,
                            'longitude' => $destinationLongitude,
                            'longitudeDelta' => null,
                        ],
                        'description' => $destination,
                        'place_id' => "",
                    ]
                ],
                'price' => (float)$request->price,
                'kilometrage' => (float)$request->km,
                'distance' => $request->distance,
                'etat_course_id' => 1,
                'types_course_id' => (int)$validatedData['type_course_id'],
                'types_trajet_id' => (int)$validatedData['type_trajet'],
                'user_id' => "admin-".auth()->user()->id,
                'comment' => null,
                'timeWaiting' => $request->time_waiting,
                'raiting' => 0,
            ]);

            $this->firebase->database()->collection('clients')->document($clientId)->update([
                ['path' => 'course_id', 'value' => $course->id()]
            ]);

            $this->firebase->database()->collection('drivers')->document($driverId)->update([
                ['path' => 'course_id', 'value' => $course->id()]
            ]);

            return redirect()->route("admin.courses")->with('success', 'Course created successfully.');
        } catch(FirebaseException $e){
            return redirect()->route("admin.courses")->with('error', 'Error while creating course.');
        }
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
            $token = $data['tokens'][0];

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
                'car' => $car,
                'token' => $token
            ];
        })->where('course_id','=', null)->all();

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

    private function check_internet_connection()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected){
            fclose($connected);
            return true;
        }
        return false;
    }
}
