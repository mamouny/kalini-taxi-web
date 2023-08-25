<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $firebase = app('firebase.firestore');
        $driversCollection = $firebase->database()->collection('drivers');
        $driverDocuments = $driversCollection->documents();

        if ($driverDocuments->isEmpty()) {
            return view("admin.drivers.index", ['drivers' => collect()]);
        }

        $drivers = collect($driverDocuments->rows())->map(function ($document) {
            $data = $document->data();
            $data['id'] = $document->id();
            return $data;
        });

        return view("admin.drivers.index", ['drivers' => $drivers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.drivers.create");
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'tel' => 'required|digits:8',
            'etat_chauffeur_id' => 'required|integer',
            'etat_disponibilite' => 'required|integer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $newDriverData = [
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'tel' => $request->input('tel'),
                'etat_chauffeur_id' => $request->input('etat_chauffeur_id'),
                'etat_disponibilite' => $request->input('etat_disponibilite'),
                'lng' => null,
                'lat' => null,
                'car' => [
                    'type_car' => '',
                    'type_course' => '',
                    'immatriculation' => '',
                ],
                'wallet' => [
                    'amount' => 0,
                ],
            ];

            // create user in firebase auth
            $auth = app('firebase.auth')->createUserWithEmailAndPassword(
                $request->input('tel') . '@kalini-ride.com',
                $request->input('password')
            );

            $newDriverData['user_id'] = $auth->uid;

            $firebase = app('firebase.firestore');
            $driversCollection = $firebase->database()->collection('drivers');
            $driversCollection->add($newDriverData);

            return redirect()->route('admin.drivers')->with('success', 'Driver created successfully');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Failed to create driver');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $firebase = app('firebase.firestore');
        $driversCollection = $firebase->database()->collection('drivers');
        $driverDocument = $driversCollection->document($id)->snapshot();

        if (!$driverDocument->exists()) {
            return redirect()->route('admin.drivers')->with('error', 'Driver not found');
        }

        $driverData = $driverDocument->data();

        return view("admin.drivers.show", ['driver' => $driverData]);
    }

    public function changeDriverState(string $id)
    {
        $firebase = app('firebase.firestore');
        $driversCollection = $firebase->database()->collection('drivers');
        $driverDocument = $driversCollection->document($id)->snapshot();

        if (!$driverDocument->exists()) {
            return redirect()->route('admin.drivers')->with('error', 'Driver not found');
        }

        $driverData = $driverDocument->data();
        $etat_chauffeur_id = $driverData['etat_chauffeur_id'];
        $etat_disponibilite = $driverData['etat_disponibilite'];

        $updated_etat_chauffeur_id = $etat_chauffeur_id == 1 ? 2 : 1;
        $updated_etat_disponibilite = $etat_disponibilite == 1 ? 2 : 1;

        try {
            $updateData = [
                ['path' => 'etat_chauffeur_id', 'value' => $updated_etat_chauffeur_id],
                ['path' => 'etat_disponibilite', 'value' => $updated_etat_disponibilite],
            ];

            $driversCollection->document($id)->update($updateData);

            return redirect()->route('admin.drivers')->with('success', 'Driver state updated successfully');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Failed to update driver');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $firebase = app('firebase.firestore');
        $driversCollection = $firebase->database()->collection('drivers');
        $driverDocument = $driversCollection->document($id)->snapshot();

        if (!$driverDocument->exists()) {
            return redirect()->route('admin.drivers')->with('error', 'Driver not found');
        }

        $driverData = $driverDocument->data();

        return view("admin.drivers.edit", ['driver' => $driverData, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'tel' => 'required|digits:8',
            'etat_chauffeur_id' => 'required|integer',
            'etat_disponibilite' => 'required|integer',
        ]);

        $firebase = app('firebase.firestore');
        $driversCollection = $firebase->database()->collection('drivers');
        $driverDocument = $driversCollection->document($id);

        try {
            $updatedDriverData = [
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'tel' => $request->input('tel'),
                'etat_chauffeur_id' => $request->input('etat_chauffeur_id'),
                'etat_disponibilite' => $request->input('etat_disponibilite'),
                'lng' => null,
                'lat' => null,
            ];

            $driverDocument->set($updatedDriverData, ['merge' => true]);

            return redirect()->route('admin.drivers')->with('success', 'Driver updated successfully');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Failed to update driver');
        }
    }

    // add car to driver
    public function storeCar(Request $request, string $id)
    {
        try {
            $firebase = app('firebase.firestore');
            $driversCollection = $firebase->database()->collection('drivers');
            $driverDocument = $driversCollection->document($id)->snapshot();

            if (!$driverDocument->exists()) {
                return redirect()->route('admin.drivers')->with('error', 'Driver not found');
            }

            $driverData = $driverDocument->data();

            $driverData['car'] = [
                'car_type' => $request->input('car_type'),
                'course_type' => $request->input('course_type'),
                'immatriculation' => $request->input('immatriculation'),
            ];

            $driversCollection->document($id)->set($driverData, ['merge' => true]);

            return redirect()->route('admin.drivers')->with('info', 'Voiture ajoutée avec succès');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Erreur lors de l\'ajout de la voiture');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $firebase = app('firebase.firestore');
        $driversCollection = $firebase->database()->collection('drivers');
        $driverDocument = $driversCollection->document($id);
        $driverData = $driverDocument->snapshot()->data();
        $userId = $driverData['user_id'];

        try {
            $auth = app('firebase.auth');
            $auth->deleteUser($userId);
            $driverDocument->delete();

            return redirect()->route('admin.drivers')->with('success', trans('Driver deleted successfully'));
        } catch (FirebaseException $e) {
            return back()->with('error', trans('Failed to delete driver'));
        }
    }
}
