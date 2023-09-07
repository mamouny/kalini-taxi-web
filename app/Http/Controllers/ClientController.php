<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    private mixed $firebase;
    private $clientsCollection;

    public function __construct()
    {
        $this->firebase = app('firebase.firestore');
        $this->clientsCollection = $this->firebase->database()->collection('clients');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientsDocuments = $this->clientsCollection->documents();

        $clients = collect($clientsDocuments->rows())->map(function ($document) {
            $data = $document->data();
            $data['id'] = $document->id();
            return $data;
        });


        return view('admin.clients.index', compact('clients'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = $this->clientsCollection->document($id)->snapshot()->data();
        return view('admin.clients.show-modal', compact('client'));

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
        $this->clientsCollection->document($id)->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
    }
}
