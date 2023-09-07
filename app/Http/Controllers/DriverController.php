<?php

namespace App\Http\Controllers;

use App\Http\Enums\DriverDisponibilityEnum;
use App\Http\Enums\DriverStateEnum;
use App\Models\DriverCar;
use App\Models\DriverDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\Auth\EmailExists;

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
            'permis_conduire' => 'required|file|mimes:png,jpg,jpeg|max:2048',
        ]);

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

        try {
            $user = app('firebase.auth')->createUserWithEmailAndPassword(
                $request->input('tel') . '@kalini-ride.com',
                $request->input('password') . '00'
            );

            $newDriverData['user_id'] = $user->uid;
            $newDriverData['password'] = $request->input('password') . '00';

            $this->driversCollection->add($newDriverData);

            $permis_conduire = $request->file('permis_conduire');
            $permis_conduireName = $user->uid . '_'. time() . '_' . $permis_conduire->getClientOriginalName();
            $permis_conduire->move(public_path('uploads/drivers/documents'), $permis_conduireName);

            $driver = new DriverDocument();
            $driver->driver_id_firebase = $user->uid;
            $driver->driver_permis_photo = $permis_conduireName;
            $driver->user_id = auth()->user()->id;
            $driver->save();

            return redirect()->route('admin.drivers')->with('success', 'Le chauffeur a été créé avec succès');

        } catch (EmailExists $e) {
            return back()->with('error', ' Le numéro de téléphone est déjà utilisé');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Failed to create driver: ' . $e->getMessage());
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

        $driverDocuments = $this->driversCollection->where('tel', '=', $request->input('tel'))->documents();

        if (!$driverDocuments->isEmpty()) {
            $driver = $driverDocuments->rows()[0];
            if ($driver->id() != $id) {
                return back()->with('error', ' Le numéro de téléphone est déjà utilisé');
            }
        }

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

            $auth = app('firebase.auth');

            $driverData = $driverDocument->snapshot()->data();
            $userId = $driverData['user_id'];

            $auth->updateUser($userId, [
                'email' => $request->input('tel') . '@kalini-ride.com',
            ]);

            $driverDocument->set($updatedDriverData, [
                'merge' => true,
            ]);

            return redirect()->route('admin.drivers')->with('success', 'Le chauffeur a été mis à jour avec succès !');
        } catch (FirebaseException $e) {
            return back()->with('error', 'Failed to update driver : ' . $e->getMessage());
        }
    }

    // add car to driver
    public function storeCar(Request $request, string $id)
    {
        $request->validate([
            'type_car' => 'required|integer',
            'type_course' => 'required|integer',
            'immatriculation' => 'required|string',
            'assurance' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'carte_grise' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'vignette' => 'required|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        try {
            $driverDocument = $this->driversCollection->document($id)->snapshot();

            if (!$driverDocument->exists()) {
                return redirect()->route('admin.drivers')->with('error', 'Driver not found');
            }

            $driverData = $driverDocument->data();
            $driverId = $driverDocument->id();
            $userId = $driverData['user_id'];

            $driverData['car'] = [
                'type_car' => (int)$request->input('type_car'),
                'type_course' => (int)$request->input('type_course'),
                'immatriculation' => $request->input('immatriculation'),
            ];

            $driverData['etat_chauffeur_id'] = DriverStateEnum::VALIDATED->value;

            $this->driversCollection->document($id)->set($driverData, ['merge' => true]);

            $assurance = $request->file('assurance');
            $carte_grise = $request->file('carte_grise');
            $vignette = $request->file('vignette');

            $assuranceName = $driverId . '_'. time() . '_' . $assurance->getClientOriginalName();
            $carte_griseName = $driverId . '_'. time() . '_' . $carte_grise->getClientOriginalName();
            $vignetteName = $driverId . '_'. time() . '_' . $vignette->getClientOriginalName();

            $assurance->move(public_path('uploads/cars/documents'), $assuranceName);
            $carte_grise->move(public_path('uploads/cars/documents'), $carte_griseName);
            $vignette->move(public_path('uploads/cars/documents'), $vignetteName);

            // save documents car documents
            $driverCarDocuments = new DriverCar();
            $driverCarDocuments->driver_id_firebase = $userId;
            $driverCarDocuments->car_assurance_photo = $assuranceName;
            $driverCarDocuments->car_carte_grise_photo = $carte_griseName;
            $driverCarDocuments->car_vignette_photo = $vignetteName;
            $driverCarDocuments->user_id = auth()->user()->id;
            $driverCarDocuments->save();

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
            'assurance' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'carte_grise' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'vignette' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        try {
            $driverDocument = $this->driversCollection->document($id)->snapshot();

            if (!$driverDocument->exists()) {
                return redirect()->route('admin.drivers')->with('error', 'Driver not found');
            }

            $driverData = $driverDocument->data();
            $driverId = $driverDocument->id();

            $carData = [
                'type_car' => $request->input('type_car'),
                'type_course' => $request->input('type_course'),
                'immatriculation' => $request->input('immatriculation'),
            ];

            // Update car data in the driver's document
            $driverData['car'] = $carData;
            $this->driversCollection->document($id)->set($driverData, ['merge' => true]);

            // Handle file uploads
            $uploadPath = public_path('uploads/cars/documents');
            $uploadedFiles = [];

            foreach (['assurance', 'carte_grise', 'vignette'] as $fileKey) {
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $fileName = $driverId . '_' . time() . '_' . $file->getClientOriginalName();
                    $file->move($uploadPath, $fileName);
                    $uploadedFiles[$fileKey] = $fileName;
                }
            }

            // Update car documents in the driver's car record
            $driverCarDocuments = DriverCar::query()->where('driver_id_firebase', $driverData['user_id'])->first();
            $driverCarDocuments?->update($uploadedFiles);

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
            $driverPermis = DriverDocument::query()->where('driver_id_firebase', '===', $userId)->first();
            $driverPermis->delete();
            // delete driver car documents
            $driverCarDocuments = DriverCar::query()->where('driver_id_firebase', '===', $userId)->get();

            foreach ($driverCarDocuments as $driverCarDocument) {
                $driverCarDocument->delete();
            }

            $auth->deleteUser($userId);
            $driverDocument->delete();

            return redirect()->route('admin.drivers')->with('success', trans('Driver deleted successfully'));
        } catch (FirebaseException $e) {
            return back()->with('error', trans('Failed to delete driver'));
        }
    }

    // wallet driver transactions

}
