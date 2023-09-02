<?php

namespace App\Http\Controllers;

use App\Http\Enums\DriverDisponibilityEnum;
use App\Http\Enums\DriverStateEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;

class DriverController extends Controller
{
    private mixed $firebase;
    private $driversCollection;

    public function __construct()
    {
        $this->firebase = app('firebase.firestore');
        $this->driversCollection = $this->firebase->database()->collection('drivers');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $driverDocuments = $this->driversCollection->documents();

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
            'password' => 'required|digits:4|confirmed',
        ]);

        try {
            $newDriverData = [
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'tel' => $request->input('tel'),
                'etat_chauffeur_id' => (int)$request->input('etat_chauffeur_id'),
                'etat_disponibilite' => (int)$request->input('etat_disponibilite'),
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
                'course_id' => null,
                'tokens' => [],
            ];

            // create user in firebase auth
            $auth = app('firebase.auth')->createUserWithEmailAndPassword(
                $request->input('tel') . '@kalini-ride.com',
                $request->input('password'). '00'
            );

            $newDriverData['user_id'] = $auth->uid;
            $newDriverData['password'] = $request->input('password'). '00';

            $this->driversCollection->add($newDriverData);

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
        $driverDocument = $this->driversCollection->document($id)->snapshot();

        if (!$driverDocument->exists()) {
            return redirect()->route('admin.drivers')->with('error', 'Driver not found');
        }

        $driverData = $driverDocument->data();

        return view("admin.drivers.show", ['driver' => $driverData]);
    }

    public function changeDriverState(string $id)
    {
        $driverDocument = $this->driversCollection->document($id)->snapshot();

        if (!$driverDocument->exists()) {
            return redirect()->route('admin.drivers')->with('error', 'Driver not found');
        }

        $driverData = $driverDocument->data();
        $etat_chauffeur_id = $driverData['etat_chauffeur_id'];
        $etat_disponibilite = $driverData['etat_disponibilite'];

        $updated_etat_chauffeur_id = $etat_chauffeur_id == DriverStateEnum::INITIAL ? DriverStateEnum::VALIDATED : DriverStateEnum::INITIAL;
        $updated_etat_disponibilite = $etat_disponibilite == DriverDisponibilityEnum::AVAILABLE ? DriverDisponibilityEnum::UNAVAILABLE : DriverDisponibilityEnum::AVAILABLE;

        try {
            $updateData = [
                ['path' => 'etat_chauffeur_id', 'value' => $updated_etat_chauffeur_id],
                ['path' => 'etat_disponibilite', 'value' => $updated_etat_disponibilite],
            ];

            $this->driversCollection->document($id)->update($updateData);

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
        $driverDocument = $this->driversCollection->document($id)->snapshot();

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

        $driverDocument = $this->driversCollection->document($id);

        try {
            $updatedDriverData = [
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'tel' => $request->input('tel'),
                'etat_chauffeur_id' => (int)$request->input('etat_chauffeur_id'),
                'etat_disponibilite' => (int)$request->input('etat_disponibilite'),
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
        $request->validate([
            'type_car' => 'required|integer',
            'type_course' => 'required|integer',
            'immatriculation' => 'required|string',
        ]);

        try {
            $driverDocument = $this->driversCollection->document($id)->snapshot();

            if (!$driverDocument->exists()) {
                return redirect()->route('admin.drivers')->with('error', 'Driver not found');
            }

            $driverData = $driverDocument->data();

            $driverData['car'] = [
                'type_car' => (int)$request->input('type_car'),
                'type_course' => (int)$request->input('type_course'),
                'immatriculation' => $request->input('immatriculation'),
            ];

            $driverData['etat_chauffeur_id'] = DriverStateEnum::VALIDATED->value;

            $this->driversCollection->document($id)->set($driverData, ['merge' => true]);

            return redirect()->route('admin.drivers')->with('success', 'Voiture ajoutée avec succès');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Erreur lors de l\'ajout de la voiture');
        }
    }

    // update car to driver
    public function updateCar(Request $request, string $id)
    {
        $request->validate([
            'type_car' => 'required|integer',
            'type_course' => 'required|integer',
            'immatriculation' => 'required|string',
        ]);

        try {
            $driverDocument = $this->driversCollection->document($id)->snapshot();

            if (!$driverDocument->exists()) {
                return redirect()->route('admin.drivers')->with('error', 'Driver not found');
            }

            $driverData = $driverDocument->data();

            $driverData['car'] = [
                'type_car' => $request->input('type_car'),
                'type_course' => $request->input('type_course'),
                'immatriculation' => $request->input('immatriculation'),
            ];

            $this->driversCollection->document($id)->set($driverData, ['merge' => true]);

            return redirect()->route('admin.drivers')->with('success', 'Voiture mise à jour avec succès');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de la voiture');
        }
    }

    // add wallet to driver
    public function storeWallet(Request $request, string $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $driverDocument = $this->driversCollection->document($id)->snapshot();

            if (!$driverDocument->exists()) {
                return redirect()->route('admin.drivers')->with('error', trans('Driver not found'));
            }

            $driverData = $driverDocument->data();

            $driverData['wallet'] = [
                'amount' => $request->input('amount'),
                'date_debut' => Carbon::now()->format('Y-m-d H:i:s'),
                'date_fin' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'),
            ];

            $driverData['etat_chauffeur_id'] = DriverStateEnum::VALIDATED_ON_RIDE->value;

            $this->driversCollection->document($id)->set($driverData, ['merge' => true]);

            return redirect()->route('admin.drivers')->with('success', 'Wallet ajouté avec succès');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Erreur lors de l\'ajout du wallet');
        }
    }

//    public function updateWallet(Request $request, string $id)
//    {
//        $request->validate([
//            'amount' => 'required|numeric|min:0',
//        ]);
//
//        try {
//            $driverDocument = $this->driversCollection->document($id)->snapshot();
//
//            if (!$driverDocument->exists()) {
//                return redirect()->route('admin.drivers')->with('error', trans('Driver not found'));
//            }
//
//            $driverData = $driverDocument->data();
//
//            if ($request->has('amount') && $request->input('amount') >= 0) {
//                $driverData['wallet'] = [
//                    'amount' => $request->input('amount'),
//                ];
//
//                $this->driversCollection->document($id)->set($driverData, ['merge' => true]);
//
//                return redirect()->route('admin.drivers')->with('success', 'Portefeuille mis à jour avec succès');
//            } else {
//                return redirect()->route('admin.drivers')->with('error', 'Le montant doit être supérieur ou égal à 0');
//            }
//        } catch (FirebaseException $e) {
//            return back()->with('error', 'Erreur lors de la mise à jour du wallet');
//        }
//    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $driverDocument = $this->driversCollection->document($id);
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
